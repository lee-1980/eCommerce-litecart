<?php

  class sm_zone_weight_warehouse {
    public $id = __CLASS__;
    public $name = 'Weight Based Shipping by Zone Warehouse';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_zone_based_shipping', 'Zone Based Shipping');
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;

    // Calculate cart weight
      $total_weight = 0;
      foreach ($items as $item) {
        $total_weight += weight::convert($item['quantity'] * $item['weight'], $item['weight_class'], $this->settings['weight_class']);
      }

      $options = array();

      for ($i=1; $i <= 3; $i++) {
        if (empty($this->settings['geo_zone_id_'.$i])) continue;

        $name = language::translate(__CLASS__.':title_option_name_zone_'.$i);

        if (!functions::reference_in_geo_zone($this->settings['geo_zone_id_'.$i], $customer['shipping_address']['country_code'], $customer['shipping_address']['zone_code'])) continue;

        $cost = self::calculate_cost($this->settings['weight_rate_table_'.$i], $total_weight);

        $options[] = array(
          'id' => 'zone_'.$i,
          'icon' => $this->settings['icon'],
          'name' => !empty($name) ? $name : reference::country($customer['shipping_address']['country_code'])->name,
          'description' => null,
          'fields' => '<div dir="ltr" style="text-align: left;" trbidi="on">
                       <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 283px;">
                       <colgroup><col style="mso-width-alt: 4937; mso-width-source: userset; width: 101pt;" width="135"></col>
                       <col style="mso-width-alt: 914; mso-width-source: userset; width: 19pt;" width="25"></col>
                       <col style="mso-width-alt: 4498; mso-width-source: userset; width: 92pt;" width="123"></col>
                       </colgroup><tbody>
                       <tr height="20" style="height: 15.0pt;">
                       <td height="20" style="height: 15.0pt; width: 101pt;" width="135">Tracking Number</td>
                       <td style="width: 19pt;" width="25">:</td>
                       <td style="width: 92pt;" width="123">Yes</td>
                       </tr>
                       <tr height="20" style="height: 15.0pt;">
                       <td class="xl65" colspan="3" height="20" style="height: 15.0pt;">
                         Accumulate your order, ship out one shot. Save shipping fee
                       </td>
                       </tr>
                       <tr height="20" style="height: 15.0pt;">
                       <td class="xl65" colspan="3" height="20" style="height: 15.0pt;">
                         Shipping fee will be calculated then.
                       </td>
                       </tr>
                       </tbody></table>
                       </div>',
          'cost' => $cost - $cost + 0.0001,
          'tax_class_id' => $this->settings['tax_class_id'],
          'exclude_cheapest' => true,
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
            'description' => null,
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
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
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
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
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
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
        ),
        array(
          'key' => 'weight_rate_table_x',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_non_matched_zones', 'Non-matched Zones') .': '. language::translate(__CLASS__.':title_weight_rate_table', 'Weight Rate Table'),
          'description' => language::translate(__CLASS__.':description_weight_rate_table', 'Ascending rate table of the shipping cost. The format must be weight:cost;weight:cost;.. (E.g. 5:8.95;10:15.95;..)'),
          'function' => 'input()',
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
          'function' => 'int()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
