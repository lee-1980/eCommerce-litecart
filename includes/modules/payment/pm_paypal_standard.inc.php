<?php

  class pm_paypal_standard {
    public $id = __CLASS__;
    public $name = 'Paypal Standard';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '2.2';
    public $website = 'https://www.paypal.com/cgi-bin/webscr?cmd=_help';
    public $priority = 0;

    public function construct() {
      $this->name = language::translate(__CLASS.':name', $this->name);
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;

      if (empty($this->settings['merchant_email'])) return;
      
      if (!empty(customer::$data['id'])) return;

      if (!empty($this->settings['geo_zone_id'])) {
        if (!functions::reference_in_geo_zone($this->settings['geo_zone_id'], $customer['country_code'], $customer['zone_code'])) return;
      }

      $option = array(
        'id' => 'card',
        'icon' => $this->settings['icon'],
        'name' => language::translate(__CLASS__.':title_card_payment', 'Card Payment'),
        'description' => language::translate(__CLASS__.':description', 'Secure and simple money transactions made by Paypal.'),
        'fields' => '',
        'cost' => '0.00001',
        'tax_class_id' => 0,
        'confirm' => language::translate(__CLASS__.':title_pay_now', 'Pay Now'),
      );

      if (!empty($this->settings['use_store_currency']) && currency::$selected['code'] != settings::get('store_currency_code')) {
        $option['description'] .= ' ' . strtr(language::translate(__CLASS__.':description_another_currency', 'For this option you will be charged in %currency_code.'), array('%currency_code' => settings::get('store_currency_code')));
      }

      return array(
        'title' => language::translate(__CLASS__.':title', 'Paypal'),
        'options' => array(
          $option,
        ),
      );
    }

    public function transfer($order) {

      $order->save(); // Creates an order id

      $request = array(
        'cmd'           => '_cart',
        'bn'            => 'Lite_Cart',
        'upload'        => '1',
        'rm'            => '0',
        'business'      => $this->settings['merchant_email'],
        'currency_code' => !empty($this->settings['use_store_currency']) ? settings::get('store_currency_code') : $order->data['currency_code'],
        'cbt'           => language::translate(__CLASS__.':title_finalize_order', 'Finalize Order'),
        'return'        => (string)document::ilink('order_process'),
        'cancel_return' => (string)document::ilink('checkout'),
        'notify_url'    => (string)document::link(WS_DIR_APP . 'ext/paypal/callback.php', array('order_id' => $order->data['id'])),
        'charset'       => language::$selected['charset'],
        'custom'        => $order->data['id'],
      );

      $item_no = 1;

    // Detect negative amounts because Paypal don't do discounts if orders include tax
      $order_contains_discount = false;
      foreach ($order->data['items'] as $item) {
        if ($item['price'] < 0) {
          $order_contains_discount = true;
          break;
        }
      }

      foreach ($order->data['order_total'] as $row) {
        if ($row['calculate'] && $row['value'] < 0) {
          $order_contains_discount = true;
          break;
        }
      }

    // Build cart containing discount - no tax specification supported
      if ($order_contains_discount) {

        foreach ($order->data['items'] as $item) {
          if ($item['price'] < 0) {
            if (!isset($request['discount_amount_cart'])) $request['discount_amount_cart'] = 0;
            $request['discount_amount_cart'] += $item['quantity'] * $this->_format_raw(-$item['price'], $order->data['currency_code'], $order->data['currency_value']);
            $request['discount_amount_cart'] += $item['quantity'] * $this->_format_raw(-$item['tax'], $order->data['currency_code'], $order->data['currency_value']);

          } else {
            $request['item_name_'.$item_no] = $item['name'];
            $request['item_number_'.$item_no] = $item['product_id'] . (!empty($item['option_id']) ? ':'.$item['product_id'] : '');
            $request['quantity_'.$item_no] = (float)$item['quantity'];
            $request['amount_'.$item_no] = $this->_format_raw($item['price'], $order->data['currency_code'], $order->data['currency_value'])
                                         + $this->_format_raw($item['tax'], $order->data['currency_code'], $order->data['currency_value']);
            $item_no++;
          }
        }

        foreach ($order->data['order_total'] as $row) {
          if ($row['calculate']) {
            if ($row['value'] < 0) {
              if (!isset($request['discount_amount_cart'])) $request['discount_amount_cart'] = 0;
              $request['discount_amount_cart'] += $this->_format_raw(-$row['value'], $order->data['currency_code'], $order->data['currency_value']);
              $request['discount_amount_cart'] += $this->_format_raw(-$row['tax'], $order->data['currency_code'], $order->data['currency_value']);
            } else {
              $request['item_name_'.$item_no] = $row['title'];
              $request['item_number_'.$item_no] = $row['module_id'];
              $request['quantity_'.$item_no] = 1;
              $request['amount_'.$item_no] = $this->_format_raw($row['value'], $order->data['currency_code'], $order->data['currency_value'])
                                           + $this->_format_raw($row['tax'], $order->data['currency_code'], $order->data['currency_value']);
              $item_no++;
            }
          }
        }

    // Build cart not containing discount - with tax specification
      } else {

        foreach ($order->data['items'] as $item) {
          $request['item_name_'.$item_no] = $item['name'];
          $request['item_number_'.$item_no] = $item['product_id'] . (!empty($item['option_id']) ? ':'.$item['option_id'] : '');
          $request['quantity_'.$item_no] = (float)$item['quantity'];
          $request['amount_'.$item_no] = $this->_format_raw($item['price'], $order->data['currency_code'], $order->data['currency_value']);
          $request['tax_'.$item_no] = $this->_format_raw($item['tax'], $order->data['currency_code'], $order->data['currency_value']);
          $item_no++;
        }

        foreach ($order->data['order_total'] as $row) {
          if (empty($row['calculate'])) continue;
          $request['item_name_'.$item_no] = $row['title'];
          $request['item_number_'.$item_no] = $row['module_id'];
          $request['quantity_'.$item_no] = '1';
          $request['amount_'.$item_no] = $this->_format_raw($row['value'], $order->data['currency_code'], $order->data['currency_value']);
          $request['tax_'.$item_no] = $this->_format_raw($row['tax'], $order->data['currency_code'], $order->data['currency_value']);
          $item_no++;
        }
      }

      if ($this->settings['gateway'] == 'Sandbox') {
        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
      } else {
        $url = 'https://www.paypal.com/cgi-bin/webscr';
      }

      return array(
        'action' => $url,
        'method' => 'post',
        'fields' => $request,
      );
    }

    public function callback($order) {

      try {

        $raw_request = file_get_contents('php://input');
        parse_str($raw_request, $txn_data);

        $client = new wrap_http();

        if ($this->settings['gateway'] == 'Sandbox') {
          $url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
        } else {
          $url = 'https://ipnpb.paypal.com/cgi-bin/webscr';
        }

        $response = $client->call('POST', $url, $raw_request . '&cmd=_notify-validate');

        if (trim($response) != 'VERIFIED') throw new Exception('Paypal could not verify IPN request ('. $response .')');

        $result = $this->verify($order, $txn_data);

        if (!empty($result['error'])) throw new Exception($result['error']);

        $order->data['payment_transaction_id'] = $txn_data['txn_id'];
        $order->data['order_status_id'] = $result['order_status_id'];
        $order->save();

      } catch(Exception $e) {
        trigger_error($e->getMessage(), E_USER_ERROR);
      }
    }

    public function verify($order, $txn_data=null) {

      try {

        if (empty($txn_data)) {

          if (empty($this->settings['pdt_auth_token'])) {
            trigger_error('Could not verify Paypal payment as no PDT token is configured', E_USER_WARNING);
            return;
          }

          if (empty($_REQUEST['tx'])) {
            throw new Exception('Could not verify the payment as no transaction id was returned by Paypal');
          }

          $request = array(
            'cmd' => '_notify-synch',
            'tx'  => $_REQUEST['tx'],
            'at'  => $this->settings['pdt_auth_token'],
          );

          if ($this->settings['gateway'] == 'Sandbox') {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
          } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr';
          }

          $headers = array(
            'User-Agent' => PLATFORM_NAME.'/'.PLATFORM_VERSION,
          );

          $client = new wrap_http();
          $response = $client->call('POST', $url, $request, $headers);

          if (empty($response)) throw new Exception('No response returned by Paypal');

          if (!$response = preg_split('#\R+#', trim($response))) {
            throw new Exception('Could not verify the payment as Paypal did not respond with any transaction data');
          }

          $txn_data = array();
          if (strcmp($response[0], 'SUCCESS') == 0) {
            for ($i=1; $i < count($response); $i++) {
              if (isset($response[$i])) {
                list($key, $val) = explode('=', $response[$i], 2);
                $txn_data[trim(urldecode($key))] = trim(urldecode($val));
              } else {
                trigger_error('Errorous Paypal PDT data in parameter '. ($i+1) .'. See paypal_pdt.log', E_USER_WARNING);
              }
            }
          } else {
            trigger_error('Paypal returned an invalid PDT response ('. $response[0] .')', E_USER_WARNING);
            throw new Exception('Invalid response returned by Paypal');
          }
        }

      // Calculate order total
        $order_total = 0;
        foreach ($order->data['items'] as $item) {
          $order_total += $this->_format_raw($item['quantity'] * $item['price'], $order->data['currency_code'], $order->data['currency_value']);
          $order_total += $this->_format_raw($item['quantity'] * $item['tax'], $order->data['currency_code'], $order->data['currency_value']);
        }

        foreach ($order->data['order_total'] as $row) {
          if (!$row['calculate']) continue;
          $order_total += $this->_format_raw($row['value'], $order->data['currency_code'], $order->data['currency_value']);
          $order_total += $this->_format_raw($row['tax'], $order->data['currency_code'], $order->data['currency_value']);
        }

        if (!empty($this->settings['use_store_currency'])) {
          if ($txn_data['mc_currency'] != settings::get('store_currency_code')) {
            throw new Exception('Payment currency should have been '. settings::get('store_currency_code') .' ('. $txn_data['mc_currency'] .')');
          }
        } else {
          if ($txn_data['mc_currency'] != $order->data['currency_code']) {
            throw new Exception('Payment currency should have been '. $order->data['currency_code'] .' ('. $txn_data['mc_currency'] .')');
          }
        }

        if (!in_array($txn_data['payment_status'], array('Pending', 'Completed'))) {
          throw new Exception('Payment status not pending or completed ('.$txn_data['payment_status'].')');
        }

        if (round($txn_data['mc_gross']) != round($order_total)) {
          throw new Exception('Payment amount '. $txn_data['mc_gross'] .' is not equal to order amount ('. $order_total .')');
        }

        if ($txn_data['receiver_email'] != $this->settings['merchant_email']) {
          throw new Exception('Invalid receiver email ('. $txn_data['receiver_email'] .')');
        }

        return array(
          'order_status_id' => $this->settings['order_status_id'],
          'transaction_id' => $txn_data['txn_id'],
        );

      } catch(Exception $e) {
        die($e->getMessage());
        return array('error' => 'Could not verify transaction:'. $e->getMessage());
      }
    }

    private function _format_raw($value, $currency_code, $currency_value) {

      if (!empty($this->settings['use_store_currency'])) {
        $currency_code = settings::get('store_currency_code');
        $currency_value = 1;
      }

      return currency::format_raw($value, $currency_code, $currency_value);
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
          'default_value' => 'images/payment/paypal.png',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'merchant_email',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_merchant_email', 'Merchant Email'),
          'description' => language::translate(__CLASS__.':description_merchant_email', 'Your Paypal registered merchant email address.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'pdt_auth_token',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_pdt_auth_token', 'PDT Auth Token'),
          'description' => language::translate(__CLASS__.':description_pdt_auth_token', 'Your Paypal PDT authorization token (see your Paypal account).'),
          'function' => 'text()',
        ),
        array(
          'key' => 'gateway',
          'default_value' => 'Sandbox',
          'title' => language::translate(__CLASS__.':title_gateway', 'Gateway'),
          'description' => language::translate(__CLASS__.':description_gateway', 'Select your Paypal payment gateway.'),
          'function' => 'radio(\'Production\',\'Sandbox\')',
        ),
        array(
          'key' => 'use_store_currency',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_use_store_currency', 'Use Store Currency'),
          'description' => language::translate(__CLASS__.':description_use_store_currency', 'Use the store currency for all transactions.'),
          'function' => 'toggle("y/n")',
        ),
        array(
          'key' => 'order_status_id',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_order_status', 'Order Status'),
          'description' => language::translate(__CLASS__.':description_order_status_success', 'Give successful orders made with this payment module the following order status.'),
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
          'function' => 'number()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
