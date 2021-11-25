<?php

  class job_nicer_prices {
    public $id = __CLASS__;
    public $name = 'Nicer Prices';
    public $description = '';
    public $author = 'TiM International';
    public $version = '1.0';
    public $support_link = '';
    public $website = 'http://www.tim-international.net';
    public $priority = 0;
    
    public function process($force=false) {
      
      if (empty($this->settings['status'])) return;
      
      if (empty($force)) {
        switch ($this->settings['check_frequency']) {
          case 'Hourly':
            if (strtotime(settings::get('nicer_prices_last_run')) > strtotime('-1 hour')) return; 
            break;
          case 'Daily':
            if (strtotime(settings::get('nicer_prices_last_run')) > strtotime('-1 day')) return; 
            break;
          case 'Weekly':
            if (strtotime(settings::get('nicer_prices_last_run')) > strtotime('-1 week')) return; 
            break;
          case 'Monthly':
            if (strtotime(settings::get('nicer_prices_last_run')) > strtotime('-1 month')) return; 
            break;
        }
      }
      
      database::query(
        "update ". DB_TABLE_SETTINGS ."
        set value = '". date('Y-m-d H:i:s') ."'
        where `key` = 'nicer_prices_last_run'
        limit 1;"
      );
      
      if (empty($this->settings['currencies'])) return;
      
      $products_query = database::query(
        "select * from ". DB_TABLE_PRODUCTS ."
        order by id;"
      );
      while ($product = database::fetch($products_query)) {
        $product = new ctrl_product($product['id']);
        
        foreach(explode(',', $this->settings['currencies']) as $currency_code) {
          if ($currency_code == settings::get('store_currency_code')) continue;
          $product->data['prices'][$currency_code] = $this->nicer_price($product->data['prices'][settings::get('store_currency_code')], $currency_code);
        }
        
        foreach (array_keys($product->data['campaigns']) as $key) {
          foreach(explode(',', $this->settings['currencies']) as $currency_code) {
            if ($currency_code == settings::get('store_currency_code')) continue;
            $new_campaign_price = $this->nicer_price($product->data['campaigns'][$key][settings::get('store_currency_code')], $currency_code);
            if ($new_campaign_price < $product->data['prices'][$currency_code]) $product->data['campaigns'][$key][$currency_code] = $new_campaign_price;
          }
        }
        
        $product->save();
      }
    }
    
    function nicer_price($value, $currency_code) {
      
      $value = currency::calculate($value, $currency_code);
      
      switch(strlen(round($value))) {
        case 1:
          $step = 1;
          break;
        case 2:
          if ($value < 50) {
            $step = 1;
          } else {
            $step = 5;
          }
          break;
        case 3:
         if ($value < 500) {
            $step = 5;
          } else {
            $step = 10;
          }
          break;
        case 4:
          $step = 50;
          break;
        case 5:
          $step = 100;
          break;
      }
      
      /*
    // Method 1 - Round up or down
      if ($step == 0 || $step == 1) {
        $rounded = round($value);
      } else {
        $rounded = (round($value / $step) * $step);
      }
      */
      
    // Method 2 - Round up
      $rounded = (ceil($value)%$step === 0) ? ceil($value) : round(($value+$step/2)/$step)*$step;
      
      if (preg_match('/[0-9]0000$/', $rounded)) $rounded -= 100;
      if (preg_match('/[0-9]000$/', $rounded)) $rounded -= 50;
      if (preg_match('/[0-9]00$/', $rounded)) $rounded -= 5;
      
      return $rounded;
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
          'key' => 'check_frequency',
          'default_value' => 'Weekly',
          'title' => language::translate(__CLASS__.':title_check_frequency', 'Check Frequency'),
          'description' => language::translate(__CLASS__.':description_check_frequency', 'How often the inconsistency check should be performed.'),
          'function' => 'radio("Hourly","Daily","Weekly","Monthly")',
        ),
        array(
          'key' => 'currencies',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_currencies', 'Currencies'),
          'description' => language::translate(__CLASS__.':description_currencies', 'A coma separated list of currencies to process.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ),
      );
    }
    
    public function install() {
      database::query(
        "insert into ". DB_TABLE_SETTINGS ."
        (title, description, `key`, value, date_created, date_updated)
        values ('Nicer Prices Last Run', 'Time when nicer prices was last processed by the background job.', 'nicer_prices_last_run', '', '". date('Y-m-d H:i:s') ."', '". date('Y-m-d H:i:s') ."');"
      );
    }
    
    public function uninstall() {
      database::query(
        "delete from ". DB_TABLE_SETTINGS ."
        where `key` = 'nicer_prices_last_run'
        limit 1;"
      );
    }
  }
  
?>