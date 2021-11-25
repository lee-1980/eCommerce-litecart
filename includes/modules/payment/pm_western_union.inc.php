<?php

  class pm_western_union {
    public $id = __CLASS__;
    public $name = 'Western Union';
    public $description = '';
    public $author = 'LinEdx';
    public $version = '1.0';
    public $website = 'http://www.litecart.libretitan.com';
    public $priority = 0;
    
    public function options($items, $subtotal, $tax, $currency_code, $customer) {
    
      if (empty($this->settings['status'])) return;
      
      if (!empty($this->settings['geo_zone_id'])) {
        if (functions::reference_in_geo_zone($this->settings['geo_zone_id'], $customer['country_code'], $customer['zone_code']) != true) return;
      }
      
      $method = array(
        'title' => language::translate(__CLASS__.':title_module', 'Western Union'),
        'options' => array(
          array(
            'id' => 'westernunion',
            'icon' => $this->settings['icon'],
            'name' => 'Full Payment',
            'description' => '',
            'fields' => '<b>Transfer full payment within 3 days</b><br><br>
             Lee Jee Szes<br>
             Maybank / Old Klang Road<br>
             1144 8600 6334<br>
             MBBEMYKLXXX<br>
             Kuala Lumpur, Malaysia<br>
             +6012 392 5533<br>
             ittoys116@gmail.com<br>
             <br>
             <b>Do not pay until you received email comfirmation on availability</b><br>
             <b>Kindly WhatsApp/Message +6012 392 5533 the (MTCN) after payment made</b>
             ',            
            'cost' => $this->settings['fee'],
            'tax_class_id' => $this->settings['tax_class_id'],
            'confirm' => language::translate(__CLASS__.':title_confirm_order', 'Confirm Order'),
          ),
        )
      );
      
      return $method;
    }
    
    public function pre_check() {
    }
    
    public function transfer($order) {
      return array(
        'action' => '',
        'method' => '',
        'fields' => '',
      );
    }
    
    public function verify($order) {
      
      return array(
        'order_status_id' => $this->settings['order_status_id'],
        'payment_transaction_id' => '',
        'errors' => '',
      );
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
          'default_value' => 'images/payment/westernunion.gif',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'fee',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_payment_fee', 'Payment Fee'),
          'description' => language::translate(__CLASS__.':description_payment_fee', 'Adds a payment fee to the order.'),
          'function' => 'int()',
        ),
        array( 
          'key' => 'westernunion_account',
          'default_value' => 'First Name: John
Last Name: Doe
Address: 300 Boylston Ave E
City: Seattle
Country: USA
Postal Code: 98102
Telephone Number: +1 6217329979', 
		  'title' => language::translate(__CLASS__.':title_westernunion_account', 'WesternUnion Acount'),
          'description' => language::translate(__CLASS__.':description_westernunion_account', 'Introduce your acount details.'),
          'function' => 'mediumtext()',
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