<?php

  class ot_discount_code {
    public $id = __CLASS__;
    public $name = 'Discount Code';
    public $description = '';
    public $author = 'TiM International';
    public $version = '1.2.1';
    public $website = 'http://www.tim-international.net';
    public $priority = 0;

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_discount_code', 'Discount Code');
    }

    public function process($order) {

      if (empty($this->settings['status'])) return;

      if (empty(session::$data['discount_code'])) return;
      
      if (!empty(customer::$data['enable_wholesale_price'])) return;
      
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

    // Calculate subtotal
      $subtotal = 0;
      foreach ($order->data['items'] as $item) {
        $subtotal += $item['quantity'] * $item['price'];
      }

    // Check subtotal
      if ($discount_code['min_subtotal_amount'] > 0 && $subtotal < (float)$discount_code['min_subtotal_amount']) {
        return;
      }

    // Check used codes by all
      $num_available_uses_total = 0;
      if (!empty($discount_code['max_use_total'])) {
        $discount_code_used_query = database::query(
          "select sum(uses) as num_used from ". DB_TABLE_DISCOUNT_CODES_USED ."
          where code = '". database::input(session::$data['discount_code']) ."';"
        );
        $num_used_total = database::fetch($discount_code_used_query);
        $num_available_uses_total = $discount_code['max_use_total'] - $num_used_total['num_used'];
        if ($num_available_uses_total <= 0) return;
      }

    // Check used codes by customer
      $num_available_uses_customer = 0;
      if (!empty($discount_code['max_use_customer'])) {
        $discount_code_used_query = database::query(
          "select sum(uses) as num_used from ". DB_TABLE_DISCOUNT_CODES_USED ."
          where code = '". database::input(session::$data['discount_code']) ."'
          and (email = '". database::input($order->data['customer']['email']) ."'
          ". ((!empty(customer::$data['id'])) ? "or customer_id = ". (int)customer::$data['id'] : "") .");"
        );
        $num_used_customer = database::fetch($discount_code_used_query);
        $num_available_uses_customer = $discount_code['max_use_customer'] - $num_used_customer['num_used'];
        if ($num_available_uses_customer <= 0) return;
        if (!empty($discount_code['max_use_total'])) {
          if ($num_available_uses_total < $num_available_uses_customer) $num_available_uses_customer = $num_available_uses_total;
        }
      }

      $discounts = array();

    // Step through each cart item
      foreach ($order->data['items'] as $item) {
        $product = new ref_product($item['product_id']);

        $discountable = array();

      // If item matches product
        if (!empty($discount_code['products'])) {
          if (in_array($product->id, explode(',', $discount_code['products']))) {
            $discountable[] = 'product';
          }
        }

      // If item matches manufacturer
        if (!empty($discount_code['manufacturers'])) {
          if (in_array($product->manufacturer_id, explode(',', $discount_code['manufacturers']))) {
            $discountable[] = 'manufacturer';
          }
        }

      // If item matches category
        if (!empty($discount_code['categories'])) {
          $category_match = false;
          foreach (explode(',', $discount_code['categories']) as $category_id) {
            if (in_array($category_id, array_keys($product->categories))) {
              $discountable[] = 'category';
              $category_match = true;
              break;
            }
          }
        }

        if (empty($discount_code['categories']) && empty($discount_code['manufacturers']) && empty($discount_code['products'])) $discountable[] = 'all';

      // Perform discount
        if (!empty($discountable) && $item['price'] != 0) {
          for ($i=1; $i<=$item['quantity']; $i++) {
            if (substr($discount_code['discount'], -1) == '%') {
              $discounts[] = array(
                'value' => (float)$item['price'] * (float)$discount_code['discount'] / 100,
                'tax' => (float)$item['tax'] * (float)$discount_code['discount'] / 100,
                'product_id' => $item['product_id'],
              );
            } else {
              $discounts[] = array(
                'value' => (float)$discount_code['discount'],
                'tax' => (float)$discount_code['discount'] * ((float)$item['tax'] / (float)$item['price']),
                'product_id' => $item['product_id'],
              );
            }
          }
        }
      }

      if (empty($discounts)) return;

      usort($discounts, function($a, $b) {
        if ($a['value'] == $b['value']) return 0;
        return ($a['value'] > $b['value']) ? -1 : 1;
      });

      $discount_sum = 0;
      $discount_sum_tax = 0;
      $discount_sum_uses = 0;

      foreach($discounts as $discount) {
 // EDITED BY ITTOYS         
 //       $discount_sum += $discount['value'];
        $discount_sum = $discount['value'];
        $discount_sum_tax += $discount['tax'];
        $discount_sum_uses++;

        if (!empty($discount_code['limited'])) {
          if (!empty($discount_code['max_use_total']) || !empty($discount_code['max_use_customer'])) {
            if (!empty($discount_code['limited']) && $discount_sum_uses == $num_available_uses_customer) break;
          }
        }
      }

      session::$data['discount_code_uses'] = !empty($discount_code['limited']) ? $discount_sum_uses : 1;

      return array(
        array(
          'title' => sprintf(language::translate(__CLASS__.':title_discount_s', 'Discount (%s)'), $discount_code['code']),
          'value' => -$discount_sum,
          'tax' => -$discount_sum_tax,
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
          'default_value' => '2',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module by the given priority value.'),
          'function' => 'number()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
