<?php

  class sm_zone_weight_express_singapore {
    public $id = __CLASS__;
    public $name = 'Weight Based Express Shipping by Zone Singapore';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';
    
    public function __construct() {
    
      $this->name = language::translate(__CLASS__.':title_zone_based_express_shipping', 'Zone Based Express Shipping');
    }
    
    public function options($items, $subtotal, $tax, $currency_code, $customer) {
      
      if (empty($this->settings['status'])) return;
      
    // Calculate cart weight
      $weight = 0;
      foreach ($items as $item) {
        $weight += weight::convert($item['quantity'] * $item['weight'], $item['weight_class'], $this->settings['weight_class']);
      }
      
      $options = array();
      
      for ($i=1; $i <= 3; $i++) {
        if (empty($this->settings['geo_zone_id_'.$i])) continue;
        
        if (!functions::reference_in_geo_zone($this->settings['geo_zone_id_'.$i], $customer['shipping_address']['country_code'], $customer['shipping_address']['zone_code'])) continue;
        
        $cost = self::calculate_cost($this->settings['weight_rate_table_'.$i], $weight);
        
        $options[] = array(
          'id' => 'zone_'.$i,
          'icon' => $this->settings['icon'],
          'name' => reference::country($customer['country_code'])->name,
          'description' => null,
          'fields' => '',
          'cost' => $cost,
          'tax_class_id' => $this->settings['tax_class_id'],
          'exclude_cheapest' => false,
        );
      }
      
      if (empty($options)) {
        if (!empty($this->settings['weight_rate_table_x'])) {
          $cost = self::calculate_cost($this->settings['weight_rate_table_x'], $weight);
          
          $options[] = array(
            'id' => 'zone_x',
            'icon' => $this->settings['icon'],
            'name' => functions::reference_get_country_name($customer['country_code']),
            'description' => null,
            'fields' => '',
            'cost' => $cost,
            'tax_class_id' => $this->settings['tax_class_id'],
          );
        } else {
          return;
        }
      }
      
      $options = array(
        'title' => $this->name,
        'options' => $options,
      );
      
      return $options;
    }
    
    private function calculate_cost($rate_table, $shipping_weight) {
      
      if (empty($rate_table)) return 0;
      
      $rate_table = explode(";" , $rate_table);
      foreach ($rate_table as $rate) {
        list($rate_weight, $rate_cost) = explode(':', $rate);
        if (!isset($cost) || $shipping_weight >= $rate_weight) {
          $cost = $rate_cost;
        }
      }
      
      return $cost;
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
          'description' => language::translate(__CLASS__.':description_status', ''),
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
          'key' => 'weight_class',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_weight_class', 'Weight Class'),
          'description' => language::translate(__CLASS__.':description_weight_class', 'The weight class for the rate table.'),
          'function' => 'weight_classes()',
        ),
        array(
          'key' => 'geo_zone_id_1',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 1: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zones()',
        ),
        array(
          'key' => 'weight_rate_table_1',
          'default_value' => '5:8.95;10:15.95',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 1: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (I.e. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
        ),
        array(
          'key' => 'geo_zone_id_2',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 2: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zones()',
        ),
        array(
          'key' => 'weight_rate_table_2',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 2: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (I.e. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
        ),
        array(
          'key' => 'geo_zone_id_3',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 3: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zones()',
        ),
        array(
          'key' => 'weight_rate_table_3',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_zone', 'Zone') .' 3: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (I.e. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
        ),
        array(
          'key' => 'weight_rate_table_x',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_non_matched_zones', 'Non-matched Zones') .': '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.'description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (I.e. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
        ),
        array(
          'key' => 'tax_class_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_tax_class', 'Tax Class'),
          'description' => language::translate(__CLASS__.':description_tax_class', 'The tax class for the shipping cost.'),
          'function' => 'tax_classes()',
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