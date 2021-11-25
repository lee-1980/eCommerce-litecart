<?php

  class ot_global_promotion {
    public $id = __CLASS__;
    public $name = 'Simple Global Promotion';
    public $description = '';
    public $author = 'DesignBox';
    public $version = '0.1 beta';
    public $website = 'https://designbox.pro';
    public $priority = 0;

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_global_promotion', 'Global Promotion');
    }

    public function process($order) {

      if (empty($this->settings['status'])) return;
	  
	  if (empty($this->settings['discount'])) return;
	  
	  if (!empty($this->settings['valid_from']) && $this->settings['valid_from'] > date('Y-m-d')) return;
	  
	  if (!empty($this->settings['valid_to']) && $this->settings['valid_to'] < date('Y-m-d') && $this->settings['valid_to'] != date('Y-m-d')) return;

      // Calculate subtotal
      $subtotal = 0;
	  $subtotal_tax = 0;
      foreach ($order->data['items'] as $item) {
        $subtotal += $item['quantity'] * $item['price'];
		$subtotal_tax += $item['quantity'] * $item['tax'];
      }

      // Check subtotal
      if ($this->settings['min_subtotal_amount'] > 0 && $subtotal < (int)$this->settings['min_subtotal_amount']) {
        return;
      }
	  
      $discount_sum = 0;
      $discount_sum_tax = 0;
	  
	  // Fixed
	  if (empty($this->settings['percantage'])) {  
		  // Subtract tax proportionally
		  $discount_sum_tax = $subtotal_tax-(((($subtotal-(float)$this->settings['discount'])*100)/$subtotal)/100)*$subtotal_tax;
		  $discount_sum = (float)$this->settings['discount']-$discount_sum_tax;
	  } else {
	  // Percentage
		  $discount_sum = (float)$subtotal * (float)$this->settings['discount'] / 100;
		  $discount_sum_tax = (float)$subtotal_tax * (float)$this->settings['discount'] / 100;
	  }
      return array(
        array(
          'title' => language::translate(__CLASS__.':title_global_promotion_summary', 'Global Promotion'),
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
        array(
          'key' => 'percantage',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_percantage', 'Percantage / Constant'),
          'description' => language::translate(__CLASS__.':description_percantage', 'Yes = percantage value of products in order | No = constant value.'),
          'function' => 'toggle("y/n")',
        ),
        array(
          'key' => 'discount',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_discount', 'Discount'),
          'description' => '',
          'function' => 'number()',
        ),
        array(
          'key' => 'min_subtotal_amount',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_min_subtotal_amount', 'Min. Subtotal Amount'),
          'description' => '',
          'function' => 'number()',
        ),
        array(
          'key' => 'valid_from',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_valid_from', 'Valid from'),
          'description' => '',
          'function' => 'datetime()',
        ),
        array(
          'key' => 'valid_to',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_valid_to', 'Valid to'),
          'description' => '',
          'function' => 'datetime()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
