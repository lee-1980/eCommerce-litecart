<?php

  class om_discount_code {
    public $id = __CLASS__;
    public $name = 'Discount Code';
    public $description = '';
    public $author = 'TiM International';
    public $version = '1.2';
    public $website = 'http://www.tim-international.net';
    public $priority = 0;

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_discount_code', 'Discount Code');
    }

    public function success($order) {

      if (empty($this->settings['status'])) return;

      if (empty(session::$data['discount_code'])) return;

      $discount_code_used_query = database::query(
        "select id from ". DB_TABLE_DISCOUNT_CODES_USED ."
        where order_id = '". (int)$order->data['id'] ."'
        limit 1;"
      );

      if (database::num_rows($discount_code_used_query)) return;

    // Get code from database
      $discount_code_query = database::query(
        "select * from ". DB_TABLE_DISCOUNT_CODES ."
        where status
        and code = '". database::input(session::$data['discount_code']) ."'
        and (customers = '' or find_in_set(". (int)$order->data['customer']['id'] .", customers))
        and (date_valid_from < '". date('Y-m-d H:i:s') ."')
        and (year(date_valid_to) <= '1971' or date_valid_to > '". date('Y-m-d H:i:s') ."')
        limit 1;"
      );
      $discount_code = database::fetch($discount_code_query);

      if (empty($discount_code)) return;

      $ot_row = array();
      foreach ($order->data['order_total'] as $row) {
        if ($row['module_id'] == 'ot_discount_code') $ot_row = $row;
      }

      if (empty($ot_row)) return;

      database::query(
        "insert into ". DB_TABLE_DISCOUNT_CODES_USED ."
        (customer_id, order_id, email, code, uses, discount, tax, date_created)
        values (". (int)$order->data['customer']['id'] .", ". (int)$order->data['id'] .", '". database::input($order->data['customer']['email']) ."', '". database::input(session::$data['discount_code']) ."', ". (int)session::$data['discount_code_uses'] .", ". -(float)$ot_row['value'] .", ". -(float)$ot_row['tax'] .", '". date('Y-m-d H:i:s') ."');"
      );

      unset(session::$data['discount_code']);

      return;
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
