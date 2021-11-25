<?php

  class sm_country_based_shipping_normal_pos_international {
    public $id = __CLASS__;
    public $name = 'Country Based Shipping Normal Pos International';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'https://www.litecart.net';

    public function __construct() {
      $this->name = language::translate(__CLASS__.':title_country_based_shipping', 'Country Based Shipping');
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer) {

      if (empty($this->settings['status'])) return;

    // Calculate cart weight
      $weight = 0;
      foreach ($items as $item) {
        $weight += weight::convert($item['quantity'] * $item['weight'], $item['weight_class'], $this->settings['weight_class']);
      }

      $cost = $this->_calculate_cost($weight, $customer['shipping_address']['country_code'], $customer['shipping_address']['postcode']);

      if ($cost === false) {
        $cost = $this->_calculate_cost($weight, 'XX');
      }

      if ($cost !== false) {
        return array(
          'title' => $this->name,
          'options' => array(
            array(
              'id' => 'country',
              'icon' => $this->settings['icon'],
              'name' => reference::country($customer['shipping_address']['country_code'])->name,
              'description' => null,
              'fields' => '<div dir="ltr" style="text-align: left;" trbidi="on"><table auto="" border="0" cellpadding="3" cellspacing="0" style="font-family: verdana, arial, helvetica, sans-serif;"><tbody>
                           <tr align="left"><td width="auto"><div class="separator" style="clear: both; text-align: center;">
                           </div>
                           <span style="color: black;">Tracking Number</span></td><td><span style="color: black;">:&nbsp;</span></td><td>Yes</td></tr>
                           <tr align="left"><td valign="top"><span style="color: black;">Estimated Delivery Time</span></td><td valign="top"><span style="color: black;">:</span></td><td valign="top">6 to 14 days</td></tr>
                           </tbody></table>
                           </div>',
              'cost' => $cost,
              'tax_class_id' => $this->settings['tax_class_id'],
              'exclude_cheapest' => false,
            ),
          ),
        );
      }
    }

    private function _calculate_cost($weight, $country_code, $postcode='') {

    // Get rate table name from destination
      foreach (preg_split('#\R+#', trim($this->settings['rate_tables_map'])) as $row) {
        list($row_country_code, $row_postcode, $row_table) = preg_split('#;#', $row);
        if (!empty($row_postcode)) $row_postcode = preg_split('#-#', $row_postcode);

        if ($row_country_code == $country_code) {

          if (empty($row_postcode)) {
            $table_name = $row_table;
            break;
          } else if ($postcode >= $row_postcode[0] && $postcode <= $row_postcode[1]) {
            $table_name = $row_table;
            break;
          }
        }
      }

      if (empty($table_name)) return false;

    // Find and eExtract rate table by name
      foreach (preg_split('#\R+#', trim($this->settings['rate_tables'])) as $row) {
        $row = preg_split('#;#', trim($row, ';'));
        if ($row[0] == $table_name) {
          $table = array_slice($row, 1);
        }
      }

      if (empty($table)) return false;

    // Calculate cost
      foreach ($table as $column) {
        list($max_weight, $cost) = preg_split('#:#', $column);
        if (!isset($fee) || $weight >= $max_weight) {
          $fee = $cost;
        }
      }

      return isset($fee) ? $fee : false;
    }

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
          'key' => 'rate_tables_map',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_rate_tables_map', 'Rate Tables Map'),
          'description' => language::translate(__CLASS__.':description_rate_tables_map', 'Line separated map of rate table mappings i.e. country_code;from_postcode-to_postcode;table_name. No postcodes given will match any postcode.'),
          'function' => 'bigtext()',
        ),
        array(
          'key' => 'rate_tables',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_rate_tables', 'Rate Tables'),
          'description' => language::translate(__CLASS__.':description_rate_tables', 'Line separated tables i.e. table_name;weight:cost;weight:cost'),
          'function' => 'bigtext()',
        ),
        array(
          'key' => 'tax_class_id',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_tax_class', 'Tax Class'),
          'description' => language::translate(__CLASS__.':description_tax_class', 'The tax class for the shipping cost.'),
          'function' => 'tax_class()',
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
