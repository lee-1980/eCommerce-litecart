<?php

  class ref_product {

    private $_id;
    private $_currency_code;
    private $_language_codes;
    private $_customer_id;
    private $_data = array();

    function __construct($product_id, $language_code=null, $currency_code=null, $customer_id=null) {

      if (empty($language_code)) $language_code = language::$selected['code'];
      if (empty($currency_code)) $currency_code = currency::$selected['code'];
      if (empty($customer_id)) $customer_id = customer::$data['id'];

      $this->_id = (int)$product_id;
      $this->_language_codes = array_unique(array(
        $language_code,
        settings::get('default_language_code'),
        settings::get('store_language_code'),
      ));
      $this->_currency_code = $currency_code;
      $this->_customer_id = $customer_id;
    }

    public function &__get($name) {

      if (array_key_exists($name, $this->_data)) {
        return $this->_data[$name];
      }

      $this->_data[$name] = null;
      $this->_load($name);

      return $this->_data[$name];
    }

    public function &__isset($name) {
      return $this->__get($name);
    }

    public function __set($name, $value) {
      trigger_error('Setting data is prohibited ('.$name.')', E_USER_ERROR);
    }

    private function _load($field) {

      switch($field) {

        case 'also_purchased_products':

          $this->_data['also_purchased_products'] = array();

            $query = database::query(
              "select oi.product_id, sum(oi.quantity) as total_quantity from ". DB_TABLE_ORDERS_ITEMS ." oi
              left join ". DB_TABLE_PRODUCTS ." p on (p.id = oi.product_id)
              where p.status
              and (oi.product_id != 0 and oi.product_id != ". (int)$this->_id .")
              and order_id in (
                select distinct order_id as id from ". DB_TABLE_ORDERS_ITEMS ."
                where product_id = ". (int)$this->_id ."
              )
              group by oi.product_id
              order by total_quantity desc;"
            );

            while ($row = database::fetch($query)) {
              $this->_data['also_purchased_products'][$row['product_id']] = reference::product($row['product_id'], $this->_language_codes[0]);
            }

          break;

        case 'attributes':

          $this->_data['attributes'] = array();

          $product_attributes_query = database::query(
            "select pa.*, ag.code, agi.name as group_name, avi.name as value_name, pa.custom_value from ". DB_TABLE_PRODUCTS_ATTRIBUTES ." pa
            left join ". DB_TABLE_ATTRIBUTE_GROUPS ." ag on (ag.id = pa.group_id)
            left join ". DB_TABLE_ATTRIBUTE_GROUPS_INFO ." agi on (agi.group_id = pa.group_id and agi.language_code = '". database::input($this->_language_codes[0]) ."')
            left join ". DB_TABLE_ATTRIBUTE_VALUES_INFO ." avi on (avi.value_id = pa.value_id and avi.language_code = '". database::input($this->_language_codes[0]) ."')
            where product_id = ". (int)$this->_id ."
            order by group_name, value_name, custom_value;"
          );

          while ($attribute = database::fetch($product_attributes_query)) {
            $this->_data['attributes'][$attribute['group_id'].'-'.$attribute['value_id']] = $attribute;
          }

          break;


        case 'oversize_parcel':
        
          $oversize_parcel_query = database::query(
              "select oversize_parcel from ". DB_TABLE_PRODUCTS_INFO ."
               where product_id = ". (int)$this->_id."
               limit 1;"
          );
          $oversize_parcel = database::fetch($oversize_parcel_query);
          if (!empty($oversize_parcel) && $oversize_parcel['oversize_parcel'] == 1) {
              $this->_data['oversize_parcel'] = true;
          }
          else{
              $this->_data['oversize_parcel'] = false;
          }
          break;

      
        case 'name':
        case 'short_description':

        case 'date_valid_from_closing':
        case 'date_valid_to_closing':
        case 'medium_description':
        case 'costing_information':
        case 'oversize_parcel':
        case 'opening_quantity':
        case 'medium_parcel':
        case 'small_parcel':
        case 'listing_info':
        case 'box_conditions':
        case 'guess_price':
        case 'shopee':
        case 'lazada':
        case 'shopee_backend':
        case 'lazada_backend':        
        
      
        case 'description':
        case 'technical_data':
        case 'head_title':
        case 'meta_description':

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_INFO ."
            where product_id = ". (int)$this->_id ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'product_id', 'language_code'))) continue;
              if (empty($this->_data[$key])) $this->_data[$key] = $value;
            }
          }

          break;

                

      case 'campaign':
  if (!empty(reference::product($this->_id)->master_insane_deal_price)) {       
    if (empty(reference::product($this->_id)->no_customer_group_prices)) {   
        if (!empty(reference::product($this->_id)->insaneprice)) { 
            if (((!empty(customer::$data['code']) && (!empty(reference::product($this->_id)->sign_in_deal)))) || ((!empty(customer::$data['code']) && (!empty(reference::product($this->_id)->signin)))))   {
            $this->_data['campaign'] = array();
            break;
    }

        $this->_data['campaign'] = array();

    if (!empty(customer::$data['id']) || (empty(customer::$data['id']))) {
        if (empty(customer::$data['disable_wholesale_price'])) {    
            $products_campaigns_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_CAMPAIGNS ."
            where product_id = ". (int)$this->_id ."
            and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
            and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
            order by end_date asc
            limit 1;"
          );



    if ($products_campaign = database::fetch($products_campaigns_query)) {
            $this->_data['campaign'] = $products_campaign;
            if ($products_campaign[$this->_currency_code] > 0) {
              $this->_data['campaign']['price'] = (float)currency::convert($products_campaign[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
            } else {
              $this->_data['campaign']['price'] = (float)$products_campaign[settings::get('store_currency_code')];
            }
           }
          }
         }
        }
    }
    }


    if (empty(reference::product($this->_id)->no_customer_group_prices)) {   
        if (!empty(reference::product($this->_id)->disable_master_insane_deal_price)) { 
            if (((!empty(customer::$data['code']) && (!empty(reference::product($this->_id)->sign_in_deal)))) || ((!empty(customer::$data['code']) && (!empty(reference::product($this->_id)->signin)))))   {
            $this->_data['campaign'] = array();
            break;
    }

        $this->_data['campaign'] = array();

    if (!empty(customer::$data['id']) || (empty(customer::$data['id']))) {
        if (empty(customer::$data['disable_wholesale_price'])) {    
            $products_campaigns_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_CAMPAIGNS ."
            where product_id = ". (int)$this->_id ."
            and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
            and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
            order by end_date asc
            limit 1;"
          );



    if ($products_campaign = database::fetch($products_campaigns_query)) {
            $this->_data['campaign'] = $products_campaign;
            if ($products_campaign[$this->_currency_code] > 0) {
              $this->_data['campaign']['price'] = (float)currency::convert($products_campaign[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
            } else {
              $this->_data['campaign']['price'] = (float)$products_campaign[settings::get('store_currency_code')];
            }
           }
          }
         }
        }
    } 

          break;
        
      


















          if ($products_campaign = database::fetch($products_campaigns_query)) {
            $this->_data['campaign'] = $products_campaign;
            if ($products_campaign[$this->_currency_code] > 0) {
              $this->_data['campaign']['price'] = (float)currency::convert($products_campaign[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
            } else {
              $this->_data['campaign']['price'] = (float)$products_campaign[settings::get('store_currency_code')];
            }
          }
          
          break;

        case 'categories':

          $this->_data['categories'] = array();

          $products_to_categories_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_TO_CATEGORIES ."
            where product_id = ". (int)$this->_id .";"
          );

          while ($product_to_category = database::fetch($products_to_categories_query)) {
            $categories_info_query = database::query(
              "select * from ". DB_TABLE_CATEGORIES_INFO ."
              where category_id = ". (int)$product_to_category['category_id'] ."
              and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
              order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
            );

            while ($row = database::fetch($categories_info_query)) {
              foreach ($row as $key => $value) {
                if (in_array($key, array('id', 'category_id', 'language_code'))) continue;
                if (empty($this->_data['categories'][$product_to_category['category_id']])) $this->_data['categories'][$product_to_category['category_id']] = $value;
              }
            }
          }

          break;

        case 'default_category':

          $this->_data['default_category'] = false;

          if (empty($this->default_category_id)) return;

          $this->_data['default_category'] = reference::category($this->default_category_id, $this->_language_codes[0]);

          break;

        case 'delivery_status':

          $this->_data['delivery_status'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_DELIVERY_STATUSES_INFO ."
            where delivery_status_id = ". (int)$this->_data['delivery_status_id'] ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'delivery_status_id', 'language_code'))) continue;
              if (empty($this->_data['delivery_status'][$key])) $this->_data['delivery_status'][$key] = $value;
            }
          }

          break;

        case 'images':

            $wishlist_query = database::query(
                "select * from ". DB_TABLE_WISHLIST ." where product_id = '". (int)$this->_id ."' and customer_id = '" . (!empty(customer::$data['id'])?(int)customer::$data['id'] : 0 )."' limit 1;"
            );
            if($wishlist = database::fetch($wishlist_query)){
                $this->_data['wishable'] = $wishlist['id'];
            }
            else{
                $this->_data['wishable'] = null;
            }

      

          $this->_data['images'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_IMAGES."
            where product_id = ". (int)$this->_id ."
            order by priority asc, id asc;"
          );
          while ($row = database::fetch($query)) {
            $this->_data['images'][$row['id']] = $row['filename'];
          }

          break;

        case 'manufacturer':

          $this->_data['manufacturer'] = array();

          if (empty($this->_data['manufacturer_id'])) return;

          $this->_data['manufacturer'] = reference::manufacturer($this->manufacturer_id, $this->_language_codes[0]);

          break;

        case 'options':

          $this->_data['options'] = array();

          $products_options_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_OPTIONS ."
            where product_id = ". (int)$this->_id ."
            order by priority;"
          );

          while ($product_option = database::fetch($products_options_query)) {

          // Group
            if (!isset($this->_data['options'][$product_option['group_id']]['id'])) {
              $option_group_query = database::query(
                "select * from ". DB_TABLE_OPTION_GROUPS ."
                where id = ". (int)$product_option['group_id'] ."
                limit 1;"
              );

              if (!$option_group = database::fetch($option_group_query)) continue;
              foreach (array('id', 'function', 'required') as $key) {
                $this->_data['options'][$product_option['group_id']][$key] = $option_group[$key];
              }
            }

            if (!isset($this->_data['options'][$product_option['group_id']]['name'])) {
              $option_group_info_query = database::query(
                "select * from ". DB_TABLE_OPTION_GROUPS_INFO ." pcgi
                where group_id = ". (int)$product_option['group_id'] ."
                and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
                order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
              );
              while ($option_group_info = database::fetch($option_group_info_query)) {
                foreach ($option_group_info as $key => $value) {
                  if (in_array($key, array('id', 'group_id', 'language_code'))) continue;
                  if (empty($this->_data['options'][$product_option['group_id']][$key])) $this->_data['options'][$product_option['group_id']][$key] = $value;
                }
              }
            }

          // Values
            if (!isset($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['id'])) {
              $option_value_query = database::query(
                "select * from ". DB_TABLE_OPTION_VALUES ."
                where id = ". (int)$product_option['value_id'] ."
                limit 1;"
              );

              if (!$option_value = database::fetch($option_value_query)) continue;
              foreach (array('id', 'value') as $key) {
                $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key] = $option_value[$key];
              }
            }

            if (!isset($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['name'])) {
              $option_values_info_query = database::query(
                "select * from ". DB_TABLE_OPTION_VALUES_INFO ." pcvi
                where value_id = ". (int)$product_option['value_id'] ."
                and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
                order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
              );

              while ($option_value_info = database::fetch($option_values_info_query)) {
                foreach ($option_value_info as $key => $value) {
                  if (in_array($key, array('id', 'value_id', 'language_code'))) continue;
                  if (empty($this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key])) $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']][$key] = $value;
                }
              }
            }

$this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['image_id'] = $product_option['image_id'];
          // Price Adjust
            $product_option['price_adjust'] = 0;

            if ((isset($product_option[$this->_currency_code]) && $product_option[$this->_currency_code] != 0) || (isset($product_option[settings::get('store_currency_code')]) && $product_option[settings::get('store_currency_code')] != 0)) {

              switch ($product_option['price_operator']) {

                case '+':
                  if ($product_option[$this->_currency_code] != 0) {
                    $product_option['price_adjust'] = currency::convert($product_option[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                  } else {
                    $product_option['price_adjust'] = $product_option[settings::get('store_currency_code')];
                  }
                  break;

                case '%':
                  if ($product_option[$this->_currency_code] != 0) {
                    $product_option['price_adjust'] = $this->price * ((float)$product_option[$this->_currency_code] / 100);
                  } else {
                    $product_option['price_adjust'] = $this->price * $product_option[settings::get('store_currency_code')] / 100;
                  }
                  break;

                case '*':
                  if ($product_option[$this->_currency_code] != 0) {
                    $product_option['price_adjust'] = $this->price * $product_option[$this->_currency_code];
                  } else {
                    $product_option['price_adjust'] = $this->price * $product_option[settings::get('store_currency_code')];
                  }
                  break;

                default:
                  trigger_error('Unknown price operator for option', E_USER_WARNING);
                  break;
              }
            }

            $this->_data['options'][$product_option['group_id']]['values'][$product_option['value_id']]['price_adjust'] = $product_option['price_adjust'];
          }

          break;

        case 'options_stock':

          $this->_data['options_stock'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
            where product_id = ". (int)$this->_id ."
            ". (!empty($option_id) ? "and id = ". (int)$option_id ."" : '') ."
            order by priority asc;"
          );

          while ($row = database::fetch($query)) {

            if (empty($row['tax_class_id'])) {
              $row['tax_class_id'] = $this->tax_class_id;
            }

            if (empty($row['sku'])) {
              $row['sku'] = $this->sku;
            }

            if (empty($row['weight']) || $row['weight'] == 0) {
              $row['weight'] = $this->weight;
              $row['weight_class'] = $this->weight_class;
            }

            if (empty($row['dim_x'])) {
              $row['dim_x'] = $this->dim_x;
              $row['dim_y'] = $this->dim_y;
              $row['dim_z'] = $this->dim_z;
              $row['dim_class'] = $this->dim_class;
            }

            $row['name'] = array();

            foreach (explode(',', $row['combination']) as $combination) {
              list($group_id, $value_id) = explode('-', $combination);

              $options_values_query = database::query(
                "select * from ". DB_TABLE_OPTION_VALUES_INFO ."
                where value_id = ". (int)$value_id ."
                and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
                order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
              );

              while ($option_value_info = database::fetch($options_values_query)) {
                foreach ($option_value_info as $key => $value) {
                  if (in_array($key, array('id', 'value_id', 'language_code'))) continue;
                  if (empty($row[$key][$option_value_info['value_id']])) $row[$key][$option_value_info['value_id']] = $value;
                }
              }
            }

            $row['name'] = implode(',', $row['name']);

            $this->_data['options_stock'][$row['id']] = $row;
          }

          break;

        case 'parents':

          $this->_data['parents'] = array();

          $query = database::query(
            "select category_id from ". DB_TABLE_PRODUCTS_TO_CATEGORIES ."
            where product_id = ". (int)$this->_id .";"
          );

          while ($row = database::fetch($query)) {
            $this->_data['parents'][$row['category_id']] = reference::category($row['category_id'], $this->_language_codes[0]);
          }

          break;


        case 'original_price': // Taken from the original 'price'

          $this->_data['price'] = 0;

          $products_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_PRICES ."
            where product_id = ". (int)$this->_id ."
            limit 1;"
          );
          $product_price = database::fetch($products_prices_query);

          if ($product_price[$this->_currency_code] != 0) {
            $this->_data['original_price'] = currency::convert($product_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['original_price'] = $product_price[settings::get('store_currency_code')];
          }

          break;
          
        case 'stock_quantity_prices': // Taken from the original 'stock_quantity_prices'

          $this->_data['stock_quantity_prices'] = 0;

          $products_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_STOCK_QUANTITY_PRICES ."
            where product_id = ". (int)$this->_id ."
            limit 1;"
          );
          $product_price = database::fetch($products_prices_query);

          if ($product_price[$this->_currency_code] != 0) {
            $this->_data['stock_quantity_prices'] = currency::convert($product_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['stock_quantity_prices'] = $product_price[settings::get('store_currency_code')];
          }

          break;    
          
          
        case 'stock_quantity_guest_prices': // Taken from the original 'stock_quantity_guest_prices'

          $this->_data['stock_quantity_guest_prices'] = 0;

          $products_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_STOCK_QUANTITY_GUEST_PRICES ."
            where product_id = ". (int)$this->_id ."
            limit 1;"
          );
          $product_price = database::fetch($products_prices_query);

          if ($product_price[$this->_currency_code] != 0) {
            $this->_data['stock_quantity_guest_prices'] = currency::convert($product_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['stock_quantity_guest_prices'] = $product_price[settings::get('store_currency_code')];
          }

          break;             
          

        case 'default_price_price': // Taken from the original 'default_price_price'

          $this->_data['default_price_price'] = 0;


            $product_default_price_price_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_DEFAULT_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $default_price_price = database::fetch($product_default_price_price_query);

          if ($default_price_price[$this->_currency_code] != 0) {
            $this->_data['default_price_price'] = currency::convert($default_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['default_price_price'] = $default_price_price[settings::get('store_currency_code')];
          }

          break; 

        case 'fake_sold_out_date_price_prices': // Taken from the original 'fake_sold_out_date_price_prices'

          $this->_data['fake_sold_out_date_price_prices'] = 0;


            $product_fake_sold_out_date_price_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_FAKE_SOLD_OUT_DATE_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $fake_sold_out_date_price_prices = database::fetch($product_fake_sold_out_date_price_prices_query);

          if ($fake_sold_out_date_price_price[$this->_currency_code] != 0) {
            $this->_data['fake_sold_out_date_price_prices'] = currency::convert($fake_sold_out_date_price_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['fake_sold_out_date_price_prices'] = $fake_sold_out_date_price_prices[settings::get('store_currency_code')];
          }

          break; 

        case 'sign_in_date_price_prices': // Taken from the original 'sign_in_date_price_prices'

          $this->_data['sign_in_date_price_prices'] = 0;


            $product_sign_in_date_price_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_SIGN_IN_DATE_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $sign_in_date_price_prices = database::fetch($product_sign_in_date_price_prices_query);

          if ($sign_in_date_price_price[$this->_currency_code] != 0) {
            $this->_data['sign_in_date_price_prices'] = currency::convert($sign_in_date_price_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['sign_in_date_price_prices'] = $sign_in_date_price_prices[settings::get('store_currency_code')];
          }

          break; 
          
          
          
        case 'guest_price_prices': // Taken from the original 'guest_price_prices'

          $this->_data['guest_price_prices'] = 0;


            $product_guest_price_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_GUEST_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $guest_price_prices = database::fetch($product_guest_price_prices_query);

          if ($guest_price_price[$this->_currency_code] != 0) {
            $this->_data['guest_price_prices'] = currency::convert($guest_price_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['guest_price_prices'] = $guest_price_prices[settings::get('store_currency_code')];
          }

          break; 
          
        case 'vip_price_prices': // Taken from the original 'vip_price_prices'

          $this->_data['vip_price_prices'] = 0;


            $product_vip_price_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_VIP_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $vip_price_prices = database::fetch($product_vip_price_prices_query);

          if ($vip_price_price[$this->_currency_code] != 0) {
            $this->_data['vip_price_prices'] = currency::convert($vip_price_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['vip_price_prices'] = $vip_price_prices[settings::get('store_currency_code')];
          }

          break;          
          
          
        case 'wholesale_price_prices': // Taken from the original 'wholesale_price_prices'

          $this->_data['wholesale_price_prices'] = 0;


            $product_wholesale_price_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_WHOLESALE_PRICE ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $wholesale_price_prices = database::fetch($product_wholesale_price_prices_query);

          if ($wholesale_price_price[$this->_currency_code] != 0) {
            $this->_data['wholesale_price_prices'] = currency::convert($wholesale_price_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['wholesale_price_prices'] = $wholesale_price_prices[settings::get('store_currency_code')];
          }

          break;           
          
          
         case 'customer_group_prices': // Taken from the original 'customer_group_prices'

          $this->_data['customer_group_prices'] != 0;

            $product_customer_group_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_CUSTOMER_GROUP ."
                  where product_id = ". (int)$this->_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
            );
          $customer_group_prices = database::fetch($product_customer_group_prices_query);

          if ($customer_group_prices[$this->_currency_code] != 0) {
            $this->_data['customer_group_prices'] = currency::convert($customer_group_prices[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['customer_group_prices'] = $customer_group_prices[settings::get('store_currency_code')];
          }

          break;
          
      
        case 'price':

          $this->_data['price'] = 0;

          $products_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_PRICES ."
            where product_id = ". (int)$this->_id ."
            limit 1;"
          );
          $product_price = database::fetch($products_prices_query);

          if ($product_price[$this->_currency_code] != 0) {
            $this->_data['price'] = currency::convert($product_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
          } else {
            $this->_data['price'] = $product_price[settings::get('store_currency_code')];
          }


            
    if (!empty($this->_customer_id) && (empty(customer::$data['code'])) && empty($this->_data['campaign']['price']) && (empty(reference::product($this->_id)->no_customer_group_prices))) {

        if (!empty(reference::customer($this->_customer_id)->customer_group_id)) {
                $product_customer_group_prices_query = database::query(
                  "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_CUSTOMER_GROUP ."
                  where product_id = ". (int)$this->_id ."
                  and customer_group_id = ". (int)reference::customer($this->_customer_id)->customer_group_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
                );

                while ($customer_group_price = database::fetch($product_customer_group_prices_query)) {
                  if (isset($customer_group_price[$this->_currency_code]) && $customer_group_price[$this->_currency_code] != 0) {
                    $this->_data['price'] = currency::convert($customer_group_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                  } else {
                    $this->_data['price'] = $customer_group_price[settings::get('store_currency_code')];
                }
            }
        }
    }
        
        else if (!empty(customer::$data['id']== 4640) && (!empty(reference::product($this->_id)->no_customer_group_prices))) {
                $product_customer_group_prices_query = database::query(
                  "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_CUSTOMER_GROUP ."
                  where product_id = ". (int)$this->_id ."
                  and customer_group_id = ". (int)reference::customer($this->_customer_id)->customer_group_id ."
                  and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                  and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                  order by id desc
                  limit 1;"
                );

                while ($customer_group_price = database::fetch($product_customer_group_prices_query)) {
                  if (isset($customer_group_price[$this->_currency_code]) && $customer_group_price[$this->_currency_code] != 0) {
                    $this->_data['price'] = currency::convert($customer_group_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                  } else {
                    $this->_data['price'] = $customer_group_price[settings::get('store_currency_code')];
                }
            }
        }
                    
          
      



            
    if(!empty(customer::$data['id']) ) {
            $products_query = database::query(
                "select quantity from ". DB_TABLE_PRODUCTS ."
            where id = ". (int)$this->_id ."
            limit 1;"
            );
            $product_info = database::fetch($products_query);
            if(isset($product_info['quantity'])){
            $product_stock_quantity_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_STOCK_QUANTITY_PRICES ."
                  where product_id = ". (int)$this->_id ."
                  and stock_quantity = ".$product_info['quantity']."
                  order by id desc
                  limit 1;"
            );

            while ($stock_quantity_price = database::fetch($product_stock_quantity_prices_query)) {
                if (isset($stock_quantity_price[$this->_currency_code]) && $stock_quantity_price[$this->_currency_code] != 0) {
                    $this->_data['price'] = currency::convert($stock_quantity_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                } else {
                    $this->_data['price'] = $stock_quantity_price[settings::get('store_currency_code')];
                }
            }
        }
    }
            
            
    if(empty(customer::$data['id']) ) {  
            $products_query = database::query(
                "select quantity from ". DB_TABLE_PRODUCTS ."
            where id = ". (int)$this->_id ."
            limit 1;"
            );
            $product_info = database::fetch($products_query);
            if(isset($product_info['quantity'])){
            $product_stock_quantity_guest_prices_query = database::query(
                "select * from ". DB_TABLE_PRODUCTS_STOCK_QUANTITY_GUEST_PRICES ."
                  where product_id = ". (int)$this->_id ."
                  and stock_quantity = ".$product_info['quantity']."
                  order by id desc
                  limit 1;"
            );

            while ($stock_quantity_guest_price = database::fetch($product_stock_quantity_guest_prices_query)) {
                if (isset($stock_quantity_guest_price[$this->_currency_code]) && $stock_quantity_guest_price[$this->_currency_code] != 0) {
                    $this->_data['price'] = currency::convert($stock_quantity_guest_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                } else {
                    $this->_data['price'] = $stock_quantity_guest_price[settings::get('store_currency_code')];
                }
            }
        }
    }
      

         
    if (!empty(reference::product($this->_id)->master_customer_special_price)) {       
        if (empty(reference::product($this->_id)->no_customer_group_prices)) { 
          if (empty(customer::$data['enable_wholesale_price'])) {
            if (!empty(customer::$data['id'])  && (empty(customer::$data['code']) && (empty(customer::$data['disable_default_price'])) )) {
                if (!empty(reference::product($this->_id)->customer_specialprice)) {  
                    $product_default_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_DEFAULT_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                          and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($default_price_price = database::fetch($product_default_price_prices_query)) {
                        if (isset($default_price_price[$this->_currency_code]) && $default_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($default_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $default_price_price[settings::get('store_currency_code')];
                        }
                    }
                }
            }
        }
      }
    }

            if (!empty(customer::$data['id']) && (!empty(customer::$data['vip']) && (empty(reference::product($this->_id)->no_customer_group_prices)))) {
                 if (!empty(customer::$data['id'])  && (empty(customer::$data['code']) && ( (!empty(customer::$data['disable_default_price'])) || (empty(customer::$data['disable_default_price'])) ))) {
                    $product_fake_sold_out_date_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_FAKE_SOLD_OUT_DATE_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                          and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($fake_sold_out_date_price_price = database::fetch($product_fake_sold_out_date_price_prices_query)) {
                        if (isset($fake_sold_out_date_price_price[$this->_currency_code]) && $fake_sold_out_date_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($fake_sold_out_date_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $fake_sold_out_date_price_price[settings::get('store_currency_code')];
                        }$this->_data['vip_price_standing'] = true ;
                    }
                }
            }
            

    if (!empty(reference::product($this->_id)->disable_master_customer_special_price)) {       
        if (empty(reference::product($this->_id)->no_customer_group_prices)) { 
            if (empty(customer::$data['enable_wholesale_price'])) {
                if (!empty(customer::$data['id'])  && (empty(customer::$data['code']) && (empty(customer::$data['disable_default_price'])) )) {
                     
                        $product_default_price_prices_query = database::query(
                       "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_DEFAULT_PRICE ."
                         where product_id = ". (int)$this->_id ."
                         and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                         and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                         order by id desc
                         limit 1;"
                        );

                        while ($default_price_price = database::fetch($product_default_price_prices_query)) {
                           if (isset($default_price_price[$this->_currency_code]) && $default_price_price[$this->_currency_code] != 0) {
                              $this->_data['price'] = currency::convert($default_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                            } else {
                               $this->_data['price'] = $default_price_price[settings::get('store_currency_code')];
                            }
                        }
                    }
                }
            }
        }
    

  if (!empty(reference::product($this->_id)->disable_master_guest_special_price)) {
        if (empty(reference::product($this->_id)->no_customer_group_prices)) {  
          if (empty(customer::$data['enable_wholesale_price'])) {
            if (empty(customer::$data['id']) || ((!empty(customer::$data['code']) && (empty(reference::product($this->_id)->sign_in_deal))) && (empty(customer::$data['disable_guest_price'])))) {
                    $product_guest_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_GUEST_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                           and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($guest_price_price = database::fetch($product_guest_price_prices_query)) {
                        if (isset($guest_price_price[$this->_currency_code]) && $guest_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($guest_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $guest_price_price[settings::get('store_currency_code')];
                        }
                    }
                }
            }
        } 
    }


        if (empty(reference::product($this->_id)->no_customer_group_prices)) {
          if (!empty(reference::product($this->_id)->master_guest_special_price)) {
            if (!empty(customer::$data['id']) && (empty(reference::product($this->_id)->customer_group_prices) && (!empty(reference::product($this->_id)->specialprice) && (empty(reference::product($this->_id)->customer_specialprice) )))) {
                  if (!empty(customer::$data['id']) && (empty(customer::$data['enable_wholesale_price']))) { 
                    $product_guest_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_GUEST_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                          and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($guest_price_price = database::fetch($product_guest_price_prices_query)) {
                        if (isset($guest_price_price[$this->_currency_code]) && $guest_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($guest_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $guest_price_price[settings::get('store_currency_code')];
                        }
                    }
                }
            } 
          }
        }

  if (!empty(reference::product($this->_id)->master_guest_special_price)) {
    if (!empty(reference::product($this->_id)->specialprice)) {  
        if (empty(reference::product($this->_id)->no_customer_group_prices)) {  
          if (empty(customer::$data['enable_wholesale_price'])) {
            if (empty(customer::$data['id']) || ((!empty(customer::$data['code']) && (empty(reference::product($this->_id)->sign_in_deal))) && (empty(customer::$data['disable_guest_price'])))) {
                    $product_guest_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_GUEST_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                           and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($guest_price_price = database::fetch($product_guest_price_prices_query)) {
                        if (isset($guest_price_price[$this->_currency_code]) && $guest_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($guest_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $guest_price_price[settings::get('store_currency_code')];
                        }
                    }
                }
            }
        } 
    }
}


    if (!empty(reference::product($this->_id)->specialprice) && (!empty(reference::product($this->_id)->disable_master_guest_special_price))) {  
        if (empty(reference::product($this->_id)->no_customer_group_prices)) {  
          if (empty(customer::$data['enable_wholesale_price'])) {
            if (empty(customer::$data['id']) || ((!empty(customer::$data['code']) && (empty(reference::product($this->_id)->sign_in_deal))) && (empty(customer::$data['disable_guest_price'])))) {
                    $product_guest_price_prices_query = database::query(
                        "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_GUEST_PRICE ."
                          where product_id = ". (int)$this->_id ."
                          and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                           and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                          order by id desc
                          limit 1;"
                    );

                    while ($guest_price_price = database::fetch($product_guest_price_prices_query)) {
                        if (isset($guest_price_price[$this->_currency_code]) && $guest_price_price[$this->_currency_code] != 0) {
                            $this->_data['price'] = currency::convert($guest_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                        } else {
                            $this->_data['price'] = $guest_price_price[settings::get('store_currency_code')];
                        }
                    }
                }
            }
        } 
    }


    if (!empty(reference::product($this->_id)->master_wholesale_special_price)) {
        if (empty(reference::product($this->_id)->no_customer_group_prices)) {		   
            if (!empty(customer::$data['id']) && (!empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($this->_id)->wholesale_specialprice) ))) {  
                if (!empty(customer::$data['enable_wholesale_price'])) {   
                     $product_wholesale_price_prices_query = database::query(
                     "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_DEFAULT_WHOLESALE_PRICE ."
                     where product_id = ". (int)$this->_id ."
                     and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                     and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                     order by id desc
                     limit 1;"
                     );

                    while ($wholesale_price_price = database::fetch($product_wholesale_price_prices_query)) {
                       if (isset($wholesale_price_price[$this->_currency_code]) && $wholesale_price_price[$this->_currency_code] != 0) {
                           $this->_data['price'] = currency::convert($wholesale_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                       } else {
                           $this->_data['price'] = $wholesale_price_price[settings::get('store_currency_code')];
                       }
                    }    
                }
            }
        }		
    }
    
    if (!empty(reference::product($this->_id)->disable_master_wholesale_special_price)) {
        if (empty(reference::product($this->_id)->no_customer_group_prices)) {		   
            if (!empty(customer::$data['id']) && (!empty(customer::$data['enable_wholesale_price']) )) {  
                if (!empty(customer::$data['enable_wholesale_price'])) {   
                     $product_wholesale_price_prices_query = database::query(
                     "select * from ". DB_TABLE_PRODUCTS_PRICES_BY_DEFAULT_WHOLESALE_PRICE ."
                     where product_id = ". (int)$this->_id ."
                     and (year(start_date) < '1971' or start_date <= '". date('Y-m-d H:i:s') ."')
                     and (year(end_date) < '1971' or end_date >= '". date('Y-m-d H:i:s') ."')
                     order by id desc
                     limit 1;"
                     );

                    while ($wholesale_price_price = database::fetch($product_wholesale_price_prices_query)) {
                       if (isset($wholesale_price_price[$this->_currency_code]) && $wholesale_price_price[$this->_currency_code] != 0) {
                           $this->_data['price'] = currency::convert($wholesale_price_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
                       } else {
                           $this->_data['price'] = $wholesale_price_price[settings::get('store_currency_code')];
                       }
                    }    
                }
            }
        }		
    }    
    
      
          break;

        case 'quantity_unit':

          $this->_data['quantity_unit'] = array(
            'id' => null,
            'decimals' => 0,
            'separate' => false,
            'name' => '',
          );

          $quantity_unit_query = database::query(
            "select id, decimals, separate from ". DB_TABLE_QUANTITY_UNITS ."
            where id = ". (int)$this->quantity_unit_id ."
            limit 1;"
          );

          if (!$this->_data['quantity_unit'] = database::fetch($quantity_unit_query)) return;

          $query = database::query(
            "select * from ". DB_TABLE_QUANTITY_UNITS_INFO ."
            where quantity_unit_id = ". (int)$this->quantity_unit_id ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );
          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'quantity_unit_id', 'language_code'))) continue;
              if (empty($this->_data['quantity_unit'][$key])) $this->_data['quantity_unit'][$key] = $value;
            }
          }

          break;


        case 'quantity_prices':

          $this->_data['quantity_prices'] = array();

          $product_quantity_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_QUANTITY_PRICES ."
            where product_id = ". (int)$this->_id ."
            order by quantity asc;"
          );
          while ($product_quantity_price = database::fetch($product_quantity_prices_query)) {
            if (!empty($product_quantity_price[$this->_currency_code]) && $product_quantity_price[$this->_currency_code] != 0) {
              $this->_data['quantity_prices'][$product_quantity_price['quantity']] = currency::convert($product_quantity_price[$this->_currency_code], $this->_currency_code, settings::get('store_currency_code'));
            } else {
              $this->_data['quantity_prices'][$product_quantity_price['quantity']] = $product_quantity_price[settings::get('store_currency_code')];
            }
          }

          break;
      
        case 'sold_out_status':

          $this->_data['sold_out_status'] = array();

          $query = database::query(
            "select id, orderable from ". DB_TABLE_SOLD_OUT_STATUSES ."
            where id = ". (int)$this->sold_out_status_id ."
            limit 1;"
          );

          if (!$this->_data['sold_out_status'] = database::fetch($query)) return;

          $query = database::query(
            "select * from ". DB_TABLE_SOLD_OUT_STATUSES_INFO ."
            where sold_out_status_id = ". (int)$this->_data['sold_out_status_id'] ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'sold_out_status_id', 'language_code'))) continue;
              if (empty($this->_data['sold_out_status'][$key])) $this->_data['sold_out_status'][$key] = $value;
            }
          }

          break;

        default:

          $query = database::query(
            "select * from ". DB_TABLE_PRODUCTS ."
            where id = ". (int)$this->_id ."
            limit 1;"
          );

          if (!$row = database::fetch($query)) return;

          foreach ($row as $key => $value) {
            switch($key) {
              case 'keywords':
                $this->_data[$key] = !empty($row[$key]) ? explode(',', $row[$key]) : array();
                break;

              default:
                $this->_data[$key] = $value;
                break;
            }
          }

          break;
      }
    }
  }
