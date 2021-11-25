<?php

  class pm_stripe_checkout {
    public $id = __CLASS__;
    public $name = 'Stripe Checkout - Card';
    public $description = '';
    public $author = 'TiM International';
    public $version = '1.0';
    public $website = 'https://www.stripe.com/';
    public $priority = 0;

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

    // If not enabled
      if (empty($this->settings['status'])) return;

    // If not in geo zone
      if (!empty($this->settings['geo_zone_id'])) {
        if (!reference::country($customer['country_code'])->in_geo_zone($customer['zone_code'], $this->settings['geo_zone_id'])) return;
      }

      $options = array(
        array(
          'id' => 'card',
          'icon' => $this->settings['icon'],
          'name' => language::translate(__CLASS__.':title_card', 'Card Payment'),
          'description' => language::translate(__CLASS__.':description_card_payment', ''),
          'fields' => '',
          'cost' => ($subtotal * 0.03) + 1 ,
          'tax_class_id' => 0,
          'confirm' => language::translate(__CLASS__.':title_pay_now', 'Pay Now'),
        ),
      );

      return array(
        'title' => $this->name,
        'options' => $options,
      );
    }

    public function transfer($order) {

      try {

        $order->save(); // Create order ID

        $request = array(
          'locale' => $order->data['language_code'],
          'line_items' => array(),
          'customer_email' => $order->data['customer']['email'],
          'client_reference_id' => $order->data['id'],
          'payment_intent_data' => array(
            'description' => 'Order ' . $order->data['id'],
          ),
          'payment_method_types' => array('card'),
          'success_url' => document::href_link('order_process'),
          'cancel_url' => document::href_link('checkout'),
        );

        foreach ($order->data['items'] as $item) {
          if ($item['price'] <= 0) continue;
          $request['line_items'][] = array(
            'images' => array(
              document::link(WS_DIR_APP .'images/'. reference::product($item['product_id'])->image),
            ),
            'name' => $item['name'],
            'quantity' => (float)$item['quantity'],
            'amount' => $this->_amount($item['price'] + $item['tax'], $order->data['currency_code'], $order->data['currency_value']),
            'currency' => $order->data['currency_code'],
          );
        }

        foreach ($order->data['order_total'] as $row) {
          if (empty($row['calculate'])) continue;
          $request['line_items'][] = array(
            'name' => $row['title'],
            'quantity' => 1,
            'amount' => $this->_amount($row['value'] + $row['tax'], $order->data['currency_code'], $order->data['currency_value']),
            'currency' => $order->data['currency_code'],
          );
        }

      // Workaround because Stripe does not support negative values
        foreach ($request['line_items'] as $item) {
          if ($item['amount'] < 0) {
            $request['line_items'] = array(array(
              'name' => 'Order '. $order->data['id'],
              'quantity' => 1,
              'amount' => $this->_amount($order->data['payment_due'], $order->data['currency_code'], $order->data['currency_value']),
              'currency' => $order->data['currency_code'],
            ));
            break;
          }
        }

        $response = $this->_call('POST', 'checkout/sessions', $request);

        if (!$json = json_decode($response, true)) {
          throw new Exception('Invalid response from Stripe');
        }

        if (!empty($json['error'])) {
          throw new Exception($json['error']['message']);
        }

        session::$data['stripe']['payment_intent_id'] = $json['payment_intent'];

        $html = <<<END
<div class="text-center">Loading...</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
  var stripe = Stripe('{$this->settings['publishable_key']}');
  stripe.redirectToCheckout({
    sessionId: '{$json['id']}'
  }).then(function(result){});
</script>
END;

        return array(
          'method' => 'html',
          'content' => $html,
        );

      } catch (Exception $e) {

        return array('error' => $e->getMessage());
      }
    }

    public function verify($order) {

      try {
        if (empty(session::$data['stripe']['payment_intent_id'])) {
          throw new Exception('Missing payment intent id');
        }

        $response = $this->_call('GET', 'payment_intents/'. session::$data['stripe']['payment_intent_id']);

        if (!$response = json_decode($response, true)) {
          throw new Exception('Invalid response from remote machine');
        }

        if (!empty($response['error'])) {
          throw new Exception($response['error']['message']);
        }

        if ($response['status'] != 'succeeded') {
          throw new Exception('Payment status not succeeded');
        }

        return array(
          'order_status_id' => $this->settings['order_status_id'],
          'transaction_id' => $response['id'],
        );

      } catch (Exception $e) {
        return array('error' => $e->getMessage());
      }
    }

    private function _amount($amount, $currency_code, $currency_value) {

    // Zero-decimal currencies
      if (in_array($currency_code, explode(',', 'BIF,CLP,DJF,GNF,JPY,KMF,KRW,MGA,PYG,RWF,UGX,VND,VUV,XAF,XOF,XPF'))) {
        return currency::format_raw($amount, $currency_code, $currency_value);
      }

      return currency::format_raw($amount, $currency_code, $currency_value) * 100;
    }

    private function _call($method, $endpoint, $request = null) {

      $client = new wrap_http();

      $headers = array(
        'Authorization' => 'Bearer '. $this->settings['secret_key'],
        'X-STRIPE-CLIENT-USER-AGENT' => json_encode(array(
          'lang' => 'php',
          'publisher' => 'litecart',
          'application' => array(
            'url' => 'https://litecart.net/',
            'version' => $this->version,
            'partner_id' => 'pp_partner_DaR9Lw5TGJwRbK',
            'name' => 'Stripe Checkout for LiteCart'
          ),
        ), JSON_UNESCAPED_SLASHES),
        'Stripe-Version' => '2019-12-03',
      );

      $response = $client->call($method, 'https://api.stripe.com/v1/'.$endpoint, $request, $headers);

      return $response;
    }

    function settings() {
      return array(
        array(
          'key' => 'status',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_status', 'Status'),
          'description' => language::translate(__CLASS__.':description_status', 'Enables or disables the module.'),
          'function' => 'toggle("e/d")',
        ),
        array(
          'key' => 'icon',
          'default_value' => 'images/payment/cards.png',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'publishable_key',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_publishable_key', 'Publishable Key'),
          'description' => language::translate(__CLASS__.':description_publishable_key', 'Your publishable key obtained by Stripe.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'secret_key',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_secret_key', 'Secret Key'),
          'description' => language::translate(__CLASS__.':description_secret_key', 'Your secret key obtained by Stripe.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'order_status_id',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_order_status', 'Order Status'),
          'description' => language::translate(__CLASS__.':description_order_status', 'Give orders made with this payment module the following order status.'),
          'function' => 'order_status()',
        ),
        array(
          'key' => 'geo_zone_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone_limitation', 'Geo Zone Limitation'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
