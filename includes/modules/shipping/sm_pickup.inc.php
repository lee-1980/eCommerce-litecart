<?php



  class sm_pickup {
    public $id = __CLASS__;
    public $name = 'Pickup';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_pickup', 'Pickup');
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;


    // If destination is not in geo zone

      if (!empty($this->settings['geo_zone_id'])) {
        if (!functions::reference_in_geo_zone($this->settings['geo_zone_id'], $customer['shipping_address']['country_code'], $customer['shipping_address']['zone_code'])) return;
      }

      $options = array(
        'title' => language::translate(__CLASS__.':title_pickup', 'Pickup'),
        'options' => array(
          array(
            'id' => 'pickup',
            'icon' => $this->settings['icon'],
            'name' => language::translate(__CLASS__.'title_option_pickup', 'Pickup'),
            'description' => null,
            'fields' => '<div dir="ltr" style="text-align: left;" trbidi="on">
                         <span style="color: #333333;"><strong>IT Toys</strong><br />
                         B5-03 KL Industrial Park,<br />
                         Jalan Klang Lama 5th Mile,<br />
                         58200 Kuala Lumpur,<br />
                         W.Persekutuan<br />
                         Malaysia</div><br />
                         <strong>Mon - Fri : 12.00pm - 4.30pm<br />
                         Sat - Sun : 12.00pm - 5.30pm</strong></span><br />',
            'cost' => '0.00001',
            'tax_class_id' => 0,
            'exclude_cheapest' => true,
          ),
        )
      );
      return $options;
}
    
    public function before_select() {}
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
          'key' => 'icon',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_icon', 'Icon'),
          'description' => language::translate(__CLASS__.':description_icon', 'Web path of the icon to be displayed.'),
          'function' => 'input()',
        ),

        array(
          'key' => 'geo_zone_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_geo_zone_limitation', 'Geo Zone Limitation'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
          'function' => 'geo_zones()',
        ),

        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module by the given priority value.'),
          'function' => 'int()',
        ),
      );
    }

    

    public function install() {}
    public function uninstall() {}

  }
    

?>