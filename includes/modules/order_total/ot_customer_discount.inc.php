<?php

  class ot_customer_discount {
    public $id = __CLASS__;
    public $name = 'Customer Discount';
    public $description = '';
    public $author = 'TiM International';
    public $version = '1.2.1';
    public $website = 'http://www.tim-international.net';
    public $priority = 0;
    
    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_customer_discount', 'Customer Discount');
    }
    
    public function process($order) {
      
      if (empty($this->settings['status'])) return;
      
      $customer_query = database::query(
        "select * from ". DB_TABLE_CUSTOMERS ."
        where status
        ". (!empty($order->data['customer']['id']) ? "and id = ". (int)$order->data['customer']['id'] : "and email = '". database::input($order->data['customer']['email']) ."'") ."
        limit 1;"
      );
      $customer = database::fetch($customer_query);
      
      if (empty($customer['discount']) || $customer['discount'] == 0) return;
      
      $subtotal = 0;
      $subtotal_tax = 0;
      foreach ($order->data['items'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $subtotal_tax += $item['tax'] * $item['quantity'];
      }
      
      $discount = $subtotal * $customer['discount'] / 100;
      $discount_tax = $discount * $subtotal_tax / $subtotal;

      return array(
        array(
          'title' => language::translate(__CLASS__.':title_discount', 'Discount'),
          'value' => -$discount,
          'tax' => -$discount_tax,
          'calculate' => true,
        ),
      );
    }
    
    public function before_process() {}
    
    public function after_process() {}
    
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
          'key' => 'priority',
          'default_value' => '20',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module by the given priority value.'),
          'function' => 'int()',
        ),
      );
    }
    
    public function install() {}
    
    public function uninstall() {}
  }
