<?php

  class om_telegram_notifications {
    public $id = __CLASS__;
    public $name = 'Telegram Notifications';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'https://www.litecart.net/';
    public $priority = 0;

    public function after_process($order) {

      if (empty($this->settings['status'])) return;

      $client = new wrap_http();

      $headers = array(
        'Content-Type' => 'application/json; charset='. language::$selected['code'],
      );

      $aliases = array(
        '%order_id' => $order->data['id'],
        '%firstname' => $order->data['customer']['firstname'],
        '%lastname' => $order->data['customer']['lastname'],
        '%billing_address' => functions::format_address($order->data['customer']),
        '%payment_transaction_id' => !empty($order->data['payment_transaction_id']) ? $order->data['payment_transaction_id'] : '-',
        '%shipping_address' => functions::format_address($order->data['customer']['shipping_address']),
        '%shipping_tracking_id' => !empty($order->data['shipping_tracking_id']) ? $order->data['shipping_tracking_id'] : '-',
        '%shipping_tracking_url' => !empty($order->data['shipping_tracking_url']) ? $order->data['shipping_tracking_url'] : '',
        '%order_items' => null,
        '%payment_due' => currency::format($order->data['payment_due'], true, $order->data['currency_code'], $order->data['currency_value']),
        '%order_copy_url' => document::ilink('order', array('order_id' => $order->data['id'], 'public_key' => $order->data['public_key']), false, array(), $order->data['language_code']),
        '%order_status' => $order->data['order_status_id'] ? reference::order_status($order->data['order_status_id'])->name : '',
        '%store_name' => settings::get('store_name'),
        '%store_url' => document::ilink('', array(), false, array(), $order->data['language_code']),
      );

      foreach ($order->data['items'] as $item) {

        if (!empty($item['product_id'])) {
          $product = reference::product($item['product_id'], $order->data['language_code']);

          $options = array();
          if (!empty($item['options'])) {
            foreach ($item['options'] as $k => $v) {
              $options[] = $k .': '. $v;
            }
          }

          $aliases['%order_items'] .= (float)$item['quantity'] .' x '. $product->name . (!empty($options) ? ' ('. implode(', ', $options) .')' : '') . "\r\n";

        } else {
          $aliases['%order_items'] .= (float)$item['quantity'] .' x '. $item['name'] . (!empty($options) ? ' ('. implode(', ', $options) .')' : '') . "\r\n";
        }
      }

      $request = array(
        'chat_id' => $this->settings['chat_id'],
        'text' => strtr($this->settings['message'], $aliases),
      );

      $result = $client->call('POST', 'https://api.telegram.org/bot'. $this->settings['bot_token'] .'/sendMessage', json_encode($request, JSON_UNESCAPED_SLASHES), $headers);
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
          'key' => 'bot_token',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_bot_token', 'Bot Token'),
          'description' => language::translate(__CLASS__.':description_bot_token', 'Your bot token as obtained by Telegram.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'chat_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_chat_id', 'Chat ID'),
          'description' => language::translate(__CLASS__.':description_chat_id', 'Unique identifier for the target chat or username of the target channel (in the format @channelusername)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'message',
          'default_value' => 'Incoming new order %order_id from %firsname %lastname with a total amount of %payment_due.',
          'title' => language::translate(__CLASS__.':title_message', 'Message'),
          'description' => language::translate(__CLASS__.':description_message', 'The message to send with the notification.'),
          'function' => 'textarea()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_module_priority', 'Process this module in the given priority order.'),
          'function' => 'number()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
