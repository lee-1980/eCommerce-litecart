<?php

  class sm_zone_weight_free_shipping {
    public $id = __CLASS__;
    public $name = 'Weight Based Shipping by Zone Malaysia';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_express_ems', 'Express EMS');
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;
      
      
 //   $payment_due = session::$data['order']->data["payment_due"] ;


    // Calculate cart weight
      $total_weight = 0;
      foreach ($items as $item) {
       if(!reference::product($item['product_id'])->free_shipping) return;
       if(reference::product($item['product_id'])->vip) return;
       $total_weight += weight::convert($item['quantity'] * (!empty($item['oversize_parcel'])? self::calculate_oversize_parcel_weight($item['dim_x'],$item['dim_y'],$item['dim_z'],$item['dim_class']):$item['weight']), $item['weight_class'], $this->settings['weight_class']);
      }

      if (!empty($this->order['subtotal'])) {

      // Calculate cart total
        $subtotal = 0;
        foreach ($order->data['items'] as $item) {
          $subtotal += $item['quantity'] * $item['price'];
        }
      }
      

//      if ($total_weight > 5) return;
      if (!empty(customer::$data['disable_shipping_module'] )) return;
      

      $options = array();

      for ($i=1; $i <= 16; $i++) {
        if (empty($this->settings['geo_zone_id_'.$i])) continue;
        

        
        $name = language::translate(__CLASS__.':title_option_name_zone_'.$i);

        if (!reference::country($customer['shipping_address']['country_code'])->in_geo_zone($customer['shipping_address']['zone_code'], $this->settings['geo_zone_id_'.$i])) continue;
        

        // $cost = $item['Dimensions'] * 10;
        $cost = self::calculate_cost($this->settings['weight_rate_table_'.$i], $total_weight);
        
        
        $options[] = array(
          'id' => 'zone_'.$i,
          'icon' => $this->settings['icon'],
          'name' => !empty($name) ? $name : reference::country($customer['shipping_address']['country_code'])->name,
          'description' => '<div dir="ltr" style="text-align: left;" trbidi="on"><table auto="" border="0" cellpadding="3" cellspacing="0" style="font-family: verdana, arial, helvetica, sans-serif;"><tbody>
                       <tr align="left"><td width="auto"><div class="separator" style="clear: both; text-align: center;">
                       </div>
                       <span style="color: black;">Tracking Number</span></td><td><span style="color: black;">:&nbsp;</span></td><td>Yes</td></tr>
                       <tr align="left"><td valign="top"><span style="color: black;">Estimated Delivery Time</span></td><td valign="top"><span style="color: black;">:</span></td><td valign="top">1 to 5 days</td></tr>
                       </tbody></table>
                       </div>',
          'fields' => 'Poslaju or Normal Pos (whichever is cheaper)',
          'cost' => $cost - $cost + 0.00000001,
          'tax_class_id' => $this->settings['tax_class_id'],
          'exclude_cheapest' => false,
        );
      }
      


      $name = language::translate(__CLASS__.':title_option_name_zone_x');

      if (empty($options)) {
        if (!empty($this->settings['weight_rate_table_x'])) {
          $cost = self::calculate_cost($this->settings['weight_rate_table_x'], $total_weight);

          $options[] = array(
            'id' => 'zone_x',
            'icon' => $this->settings['icon'],
            'name' => !empty($name) ? $name : reference::country($customer['shipping_address']['country_code'])->name,
            'description' => 'test',
            'fields' => '',
            'cost' => $cost + $this->settings['handling_fee'],
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

    private function calculate_oversize_parcel_weight( $dim_x = 0, $dim_y = 0, $dim_z = 0, $dim_class = "cm") {
      if ((empty($dim_x) && $dim_x == 0) || (empty($dim_y) && $dim_y == 0) || (empty($dim_z) && $dim_z == 0)) return 0;

      $weight = length::convert($dim_x, $dim_class, "cm") * length::convert($dim_y, $dim_class, "cm") * length::convert($dim_z, $dim_class, "cm") / 6000;

      return $weight;
    }
    
    private function calculate_cost($rate_table, $shipping_weight) {

      if (empty($rate_table)) return 0;

      $cost = 0;

      switch ($this->settings['method']) {

        case '<':
        case '&lt;':
          foreach (array_reverse(preg_split('#[\|;]#', $rate_table)) as $rate) {
            list($rate_weight, $rate_cost) = explode(':', $rate);
            if ($shipping_weight < $rate_weight) {
              $cost = $rate_cost;
            }
          }
          break;

        case '<=':
        case '&lt;=':
          foreach (array_reverse(preg_split('#[\|;]#', $rate_table)) as $rate) {
            list($rate_weight, $rate_cost) = explode(':', $rate);
            if ($shipping_weight <= $rate_weight) {
              $cost = $rate_cost;
            }
          }
          break;

        case '>':
        case '&gt;':

          foreach (preg_split('#[\|;]#', $rate_table) as $rate) {
            list($rate_weight, $rate_cost) = explode(':', $rate);
            if ($shipping_weight > $rate_weight) {
              $cost = $rate_cost;
            }
          }
          break;

        case '>=':
        case '&gt;=':
        default:
          foreach (preg_split('#[\|;]#', $rate_table) as $rate) {
            list($rate_weight, $rate_cost) = explode(':', $rate);
            if ($shipping_weight >= $rate_weight) {
              $cost = $rate_cost;
            }
          }
          break;
      }

      return $cost;
    }

    public function select() {}

    public function after_process() {}

    public function settings() {
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
          'function' => 'text()',
        ),
        array(
          'key' => 'weight_class',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_weight_class', 'Weight Class'),
          'description' => language::translate(__CLASS__.':description_weight_class', 'The weight class for the rate table.'),
          'function' => 'weight_class()',
        ),
        array(
          'key' => 'geo_zone_id_1',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_johor', 'Johor') .' 1: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_1',
          'default_value' => '5:8.95;10:15.95',
          'title' => language::translate(__CLASS__.':title_title_johor', 'Johor') .' 1: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_2',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_kedah', 'Kedah') .' 2: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_2',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_kedah', 'Kedah') .' 2: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_3',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_kelantan', 'Kelantan') .' 3: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_3',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_kelantan', 'Kelantan') .' 3: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_4',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_melaka', 'Melaka') .' 4: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_4',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_melaka', 'Melaka') .' 4: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),		
        array(
          'key' => 'geo_zone_id_5',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_negeri_sembilan', 'Negeri Sembilan') .' 5: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Negeri Sembilan'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_5',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_negeri_sembilan', 'Negeri Sembilan') .' 5: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 6:8.96;10:16.96;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_6',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_pahang', 'Pahang') .' 6: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Pahang'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_6',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_pahang', 'Pahang') .' 6: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 7:8.97;10:17.97;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_7',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_perak', 'Perak') .' 7: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Perak'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_7',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_perak', 'Perak') .' 7: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 8:8.98;10:18.98;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_8',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_perlis', 'Perlis') .' 8: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Perlis'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_8',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_perlis', 'Perlis') .' 8: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 9:9.99;10:19.99;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_9',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_pulau_pinang', 'Pulau Pinang') .' 9: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Pulau Pinang'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_9',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_pulau_pinang', 'Pulau Pinang') .' 9: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 10:10.1010;10:110.1010;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_10',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_sabah', 'Sabah') .' 10: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Sabah'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_10',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_sabah', 'Sabah') .' 10: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 11:11.1111;11:111.1111;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_11',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_sarawak', 'Sarawak') .' 11: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Sarawak'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_11',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_sarawak', 'Sarawak') .' 11: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 12:12.1212;12:121.1212;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_12',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_selangor', 'Selangor') .' 12: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Selangor'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_12',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_selangor', 'Selangor') .' 12: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 13:13.1313;13:131.1313;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_13',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_terengganu', 'Terengganu') .' 13: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Terengganu'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_13',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_terengganu', 'Terengganu') .' 13: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 13:13.1313;13:131.1313;..)'),
          'function' => 'text()',
        ),
        array(
          'key' => 'geo_zone_id_14',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_wilayah_persekutuan', 'Wilayah Persekutuan') .' 14: '. language::translate(__CLASS__.':title_geo_zone', 'Geo Zone'),
          'description' => language::translate(__CLASS__.':description_geo_zone', 'Geo zone to which the cost applies.'),
          'function' => 'geo_zone()',
        ),
        array(
          'key' => 'weight_rate_table_14',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_wilayah_persekutuan', 'Wilayah Persekutuan') .' 14: '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),        
        array(
          'key' => 'weight_rate_table_x',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_non_matched_zones', 'Non-matched Zones') .': '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'text()',
        ),		
		
        array(
          'key' => 'method',
          'default_value' => '>=',
          'title' => language::translate(__CLASS__.':title_method', 'Method'),
          'description' => language::translate(__CLASS__.':description_method', 'The calculation method that should to be used for the rate tables where a condition is met for shipping weight. E.g. weight < table'),
          'function' => 'select("&lt;","&lt;=","&gt;","&gt;=")',
        ),
        array(
          'key' => 'handling_fee',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_handling_fee', 'Handling Fee'),
          'description' => language::translate(__CLASS__.':description_handling_fee', 'Enter your handling fee for the shipment.'),
          'function' => 'float()',
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
          'function' => 'number()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
