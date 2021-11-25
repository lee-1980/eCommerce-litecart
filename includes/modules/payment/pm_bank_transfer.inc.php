<?php

  class pm_bank_transfer {
    public $id = __CLASS__;
    public $name = 'Bank Transfer';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.1';
    public $website = 'http://www.litecart.net';
    public $priority = 0;
    
    public function options($items, $subtotal, $tax, $currency_code, $customer) {
      
      if (empty($this->settings['status'])) return;
      
      for ($i=1; $i <= 16; $i++) {
        if (empty($this->settings['geo_zone_id_'.$i])) continue;

        $name = language::translate(__CLASS__.':title_option_name_zone_'.$i);

        if (!reference::country($customer['shipping_address']['country_code'])->in_geo_zone($customer['shipping_address']['zone_code'], $this->settings['geo_zone_id_'.$i])) continue;
        
      $method = array(
        'title' => language::translate(__CLASS__.':title_invoice', ''),
        'options' => array(
          array(
            'id' => 'invoice',
            'icon' => $this->settings['icon'],
            'name' => language::translate(__CLASS__.':title_option_title', ''),
            'description' => '<div id="clock"></div>',
            'fields' => '<span style="color: black;"><b>Bank transfer full payment within 24 hours upon email notification on payment request</b><br><br>
             Lee Jee Szes<br>
             Maybank / Old Klang Road<br>
             1144 8600 6334<br>
             MBBEMYKLXXX<br>
             Kuala Lumpur, Malaysia<br>
             ittoys116@gmail.com<br>
             <br>
             <b>We only accept MYR for Bank Transfer. </b>
             <br>
             <b>Do not pay until you received email comfirmation on availability.</b>
             </br>
             <b>Kindly Whatsapp +6012 392 5533 after making payment for reference.</b>
             </br>
             <b>Please key in your Order No (Number only) in reference column as reference.</b>
             </br>
             </br>
             Click <b><a href="https://ittoysline.com/regional_settings"></a><a href="https://ittoysline.com/regional_settings">here</a></b> to change currency
      

      
            ',
            'cost' => '0.00001',
            'tax_class_id' => $this->settings['tax_class_id'],
            'confirm' => language::translate(__CLASS__.':title_confirm_order', 'Confirm Order'),
          ),
        )
      );
      
   
      
      return $method;
    }
    }    
    public function pre_check($order) {
      if (!empty($this->settings['require_tax_id'])) {
        if (empty($order->data['customer']['tax_id'])) return language::translate('error_must_provide_tax_id', 'You must provide a tax ID for selecting invoice payment');
      }
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
          'key' => 'geo_zone_id_2',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone_2', 'Geo Zone 2'),
          'description' => language::translate(__CLASS__.':description_geo_zone_2', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zones()',
        ),  
        array(
          'key' => 'geo_zone_id_3',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone_3', 'Geo Zone 3'),
          'description' => language::translate(__CLASS__.':description_geo_zone_3', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zones()',
        ),     
        array(
          'key' => 'geo_zone_id_4',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone_4', 'Geo Zone 4'),
          'description' => language::translate(__CLASS__.':description_geo_zone_4', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
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

<script>
    if (document.getElementById("clock")) document.getElementById("clock").innerHTML = formatAMPM();


    function formatAMPM() {
        var d = new Date(),
            minutes = d.getMinutes().toString().length == 1 ? '0' + d.getMinutes() : d.getMinutes(),
            hours = d.getHours().toString().length == 1 ? '0' + d.getHours() : d.getHours(),
            ampm = d.getHours() >= 12 ? 'pm' : 'am',
            months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        return months [d.getMonth()] + ' ' + d.getDate() + ' ' + d.getFullYear() + ' ' + hours + ':' + minutes + ampm;
    }
</script>
