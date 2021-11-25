<?php

  class pm_binance {
    public $id = __CLASS__;
    public $name = 'Binance';
    public $description = '';
    public $author = 'IT Toys';
    public $version = '1.1';
    public $website = 'https://ittoysline.com/en/';
    public $priority = 0;
    
    public function options($items, $subtotal, $tax, $currency_code, $customer) {
      
      if (empty($this->settings['status'])) return;
      
      if (!empty($this->settings['geo_zone_id'])) {
        if (functions::reference_in_geo_zone($this->settings['geo_zone_id'], $customer['country_code'], $customer['zone_code']) != true) return;
      }
      
      $method = array(
        'title' => '<b>Binance Transfer</b>',
        'options' => array(
          array(
            'id' => 'invoice',
            'icon' => $this->settings['icon'],
            'name' => 'E-Wallet',
            'description' => '<b>Binance ID : 147251259</b>',
            'cost' => '0.00001',
            'fields' => '<b>Do not pay until you received email comfirmation on availability.</b>',
            'tax_class_id' => $this->settings['tax_class_id'],
            'confirm' => language::translate(__CLASS__.':title_confirm_order', 'Confirm Order'),
            'function' => 'decimal()',
          ),
        )
      );
      
  
      
      foreach (cart::$items as $item) {
        if (!empty($item['product_id'])) {
          $product = new ref_product($item['product_id']);
          if (empty($this->settings['include_campaign_products'])) {
            if (empty($product->preorderable )) continue;
            if (!empty($product->addtocart )) return; 
          }
        }
        
      return $method;
    }
    }
    
    public function pre_check($order) {
      if (!empty($this->settings['require_tax_id'])) {
        if (empty($order->data['customer']['tax_id'])) return language::translate('error_must_provide_tax_id', 'You must provide a tax ID for selecting invoice payment');
      }
    }
    

    
    public function verify($order) {
      return array(
        'order_status_id' => $this->settings['order_status_id'],
        'payment_transaction_id' => '',
        'errors' => '',
      );
    }
    
    public function after_process($order) {
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
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'require_tax_id',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_require_tax_id', 'Require Tax ID'),
          'description' => language::translate(__CLASS__.':description_require_tax_id', 'Require that the customer has filled out the Tax ID field.'),
          'function' => 'toggle("y/n")',
        ),
        array(
          'key' => 'fee',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_payment_fee', 'Payment Fee'),
          'description' => language::translate(__CLASS__.':description_payment_fee', 'Adds a payment fee to the order.'),
          'function' => 'decimal()',
        ),
        array(
          'key' => 'tax_class_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_tax_class', 'Tax Class'),
          'description' => language::translate(__CLASS__.':description_tax_class', 'The tax class for the shipping cost.'),
          'function' => 'tax_classes()',
        ),
        array(
          'key' => 'order_status_id',
          'default_value' => '0',
          'title' => language::translate('title_order_status', 'Order Status'),
          'description' => language::translate('modules:description_order_status', 'Give orders made with this payment method the following order status.'),
          'function' => 'order_status()',
        ),
        array(
          'key' => 'geo_zone_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zones()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate('title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Displays this module by the given priority order value.'),
          'function' => 'int()',
        ),
      );
    }
    
    public function install() {}
    
    public function uninstall() {}
  }
  
?>