<?php

  class ent_order {
    public $data;
    public $previous;

    public function __construct($order_id=null) {

      if (!empty($order_id)) {
        $this->load($order_id);
      } else {
        $this->reset();
      }
    }

    public function reset() {

      $this->data = array();

      $fields_query = database::query(
        "show fields from ". DB_TABLE_ORDERS .";"
      );

      while ($field = database::fetch($fields_query)) {

        switch ($field['Field']) {
          case 'customer_id':
          case 'customer_email':
          case 'customer_tax_id':
          case 'customer_company':
          case 'customer_firstname':
          case 'customer_lastname':
          case 'customer_address1':
          case 'customer_address2':
          case 'customer_postcode':
          case 'customer_city':
          case 'customer_country_code':
          case 'customer_zone_code':
          case 'customer_phone':
            $this->data['customer'][preg_replace('#^(customer_)#', '', $field['Field'])] = null;
            break;

          case 'shipping_company':
          case 'shipping_firstname':
          case 'shipping_lastname':
          case 'shipping_address1':
          case 'shipping_address2':
          case 'shipping_postcode':
          case 'shipping_city':
          case 'shipping_country_code':
          case 'shipping_zone_code':
          case 'shipping_phone':
            $this->data['customer']['shipping_address'][preg_replace('#^(shipping_)#', '', $field['Field'])] = null;
            break;

          case 'payment_option_id':
          case 'payment_option_name':
            $this->data['payment_option'][preg_replace('#^(payment_option_)#', '', $field['Field'])] = null;
            break;

          case 'shipping_option_id':
          case 'shipping_option_name':
            $this->data['shipping_option'][preg_replace('#^(shipping_option_)#', '', $field['Field'])] = null;
            break;

          default:
            $this->data[$field['Field']] = null;
            break;
        }
      }

      $this->data = array_merge($this->data, array(
        'uid' => uniqid(),
        'weight_class' => settings::get('store_weight_class'),
        'currency_code' => currency::$selected['code'],
        'currency_value' => currency::$selected['value'],
        'language_code' => language::$selected['code'],
        'items' => array(),
        'order_total' => array(),
        'comments' => array(),
        'subtotal' => array('amount' => 0, 'tax' => 0),
        'display_prices_including_tax' => settings::get('default_display_prices_including_tax'),
      ));

      $this->previous = $this->data;
    }

    private function load($order_id) {

      if (!preg_match('#^[0-9]+$#', $order_id)) throw new Exception('Invalid order (ID: '. $order_id .')');

      $this->reset();

      $order_query = database::query(
        "select * from ". DB_TABLE_ORDERS ."
        where id = ". (int)$order_id ."
        limit 1;"
      );

      if ($order = database::fetch($order_query)) {
        $this->data = array_replace($this->data, array_intersect_key($order, $this->data));
      } else {
        throw new Exception('Could not find order in database (ID: '. (int)$order_id .')');
      }

      foreach ($order as $field => $value) {

        switch ($field) {
          case 'customer_id':
          case 'customer_email':
          case 'customer_tax_id':
          case 'customer_company':
          case 'customer_firstname':
          case 'customer_lastname':
          case 'customer_address1':
          case 'customer_address2':
          case 'customer_postcode':
          case 'customer_city':
          case 'customer_country_code':
          case 'customer_zone_code':
          case 'customer_phone':
            $this->data['customer'][preg_replace('#^(customer_)#', '', $field)] = $value;
            break;

          case 'shipping_company':
          case 'shipping_firstname':
          case 'shipping_lastname':
          case 'shipping_address1':
          case 'shipping_address2':
          case 'shipping_postcode':
          case 'shipping_city':
          case 'shipping_country_code':
          case 'shipping_zone_code':
          case 'shipping_phone':
            $this->data['customer']['shipping_address'][preg_replace('#^(shipping_)#', '', $field)] = $value;
            break;

          case 'payment_option_id':
          case 'payment_option_name':
            $this->data['payment_option'][preg_replace('#^(payment_option_)#', '', $field)] = $value;
            break;

          case 'shipping_option_id':
          case 'shipping_option_name':
            $this->data['shipping_option'][preg_replace('#^(shipping_option_)#', '', $field)] = $value;
            break;
        }
      }

      $order_items_query = database::query(
        "select * from ". DB_TABLE_ORDERS_ITEMS ."
        where order_id = ". (int)$order_id ."
        order by id;"
      );

      while ($item = database::fetch($order_items_query)) {
        $item['options'] = unserialize($item['options']);
        $item['quantity'] = (float)$item['quantity']; // Turn "1.0000" to 1
        $item['price'] = (float)$item['price']; // Turn "1.0000" to 1
        $item['tax'] = (float)$item['tax']; // Turn "1.0000" to 1
        $this->data['items'][$item['id']] = $item;
      }

      $order_totals_query = database::query(
        "select * from ". DB_TABLE_ORDERS_TOTALS ."
        where order_id = ". (int)$order_id ."
        order by priority;"
      );


      $this->data['order_original_grandtotal'] = $this->data['payment_due'];
      $this->data['order_discount_code'] = 0;
      $this->data['payment_received'] = 0;
      $this->data['order_shipping_fee'] = 0;
      
            $customer_payment = isset($customer_payment)?$customer_payment:0;
			$discount_code = isset($discount_code)?$discount_code:0;
			$customer_discount = isset($customer_discount)?$customer_discount:0;
			$shipping_fee = isset($shipping_fee)?$shipping_fee:0;
			$payment_fee = isset($payment_fee)?$payment_fee:0;
			$fees = isset($fees)?$fees:0;
			$discount_nth_item = isset($discount_nth_item)?$discount_nth_item:0;
			$installment_fee = isset($installment_fee)?$installment_fee:0;

      
      while ($row = database::fetch($order_totals_query)) {
        $row['value'] = (float)$row['value']; // Turns "1.0000" to 1
        $row['tax'] = (float)$row['tax']; // Turns "1.0000" to 1
        $this->data['order_total'][$row['id']] = $row;

					    if ($row["module_id"] == 'ot_customer_payment') {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$customer_payment 	-= $subval + $tax;	
							
						}  else if ($row["module_id"] == 'ot_discount_code') {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$discount_code 	-= $subval + $tax;								
							
						} else if (strpos($row["module_id"], 'shipping') !== false) {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$shipping_fee 	+= $subval + $tax;
							
						} else if (strpos($row["module_id"], 'payment') !== false) {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$payment_fee 	+= $subval + $tax;
							
						} else if (strpos($row["module_id"], 'discount_nth_item') !== false) {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$discount_nth_item 	-= $subval + $tax;								

						} else if (strpos($row["module_id"], 'installment') !== false) {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$installment_fee 	+= $subval + $tax;

						} else if (strpos($row["module_id"], 'customer_discount') !== false) {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$customer_discount 	-= $subval + $tax;	
							
						} else {
							$subval = (double) (isset($row["value"])) ? $row["value"] : 0; 
							$tax 	= (double) (isset($row["tax"])) ? $row["tax"] : 0; 
							$fees 			+= $subval + $tax;
						}
      
      }


			
            $this->data['order_original_grandtotal'] += ($customer_payment + $discount_code + $customer_discount);
            $this->data['payment_received'] += ($customer_payment + $discount_code);
            $this->data['order_shipping_fee'] = $shipping_fee;
            $this->data['discount_code'] = $discount_code;
      
      $order_comments_query = database::query(
        "select * from ". DB_TABLE_ORDERS_COMMENTS ."
        where order_id = ". (int)$order_id ."
        order by id;"
      );

      while ($row = database::fetch($order_comments_query)) {
        $this->data['comments'][$row['id']] = $row;
      }

      $this->previous = $this->data;
    }

    public function save() {

    // Re-calculate total if there are changes
      $this->refresh_total();

    // Previous order status
      if (!empty($this->previous)) {
        $previous_order_status_query = database::query(
          "select os.*, osi.name, osi.email_message from ". DB_TABLE_ORDER_STATUSES ." os
          left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (os.id = osi.order_status_id and osi.language_code = '". database::input($this->data['language_code']) ."')
          where os.id = ". (int)$this->previous['order_status_id'] ."
          limit 1;"
        );

        $previous_order_status = database::fetch($previous_order_status_query);
      }

    // Current order status
      $current_order_status_query = database::query(
        "select os.*, osi.name, osi.email_message from ". DB_TABLE_ORDER_STATUSES ." os
        left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (os.id = osi.order_status_id and osi.language_code = '". database::input($this->data['language_code']) ."')
        where os.id = ". (int)$this->data['order_status_id'] ."
        limit 1;"
      );

      $current_order_status = database::fetch($current_order_status_query);

    // Log order status change as comment
      if (!empty($this->previous) && ($this->data['order_status_id'] != $this->previous['order_status_id'])) {
        $this->data['comments'][] = array(
          'author' => 'system',
          'text' => strtr(language::translate('text_order_status_changed_to_new_status', 'Order status changed to %new_status'), array('%new_status' => $current_order_status['name'])),
          'hidden' => 1,
        );
      }

    // Link guests to customer profile
      if (empty($this->data['customer']['id']) && !empty($this->data['customer']['email'])) {
        $customers_query = database::query(
          "select id from ". DB_TABLE_CUSTOMERS ."
          where email = '". database::input($this->data['customer']['email']) ."'
          limit 1;"
        );

        if ($customer = database::fetch($customers_query)) {
          $this->data['customer']['id'] = $customer['id'];
        }
      }

      if (empty($this->data['public_key'])) {
        $this->data['public_key'] = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', mt_rand(5, 10))), 0, 32);
      }

    // Insert/update order
      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_ORDERS ."
          (uid, client_ip, user_agent, domain, date_created)
          values ('". database::input($this->data['uid']) ."', '". database::input($_SERVER['REMOTE_ADDR']) ."', '". database::input($_SERVER['HTTP_USER_AGENT']) ."', '". database::input($_SERVER['HTTP_HOST']) ."', '". ($this->data['date_created'] = date('Y-m-d H:i:s')) ."');"
        );

        $this->data['id'] = database::insert_id();
      }

      database::query(
        "update ". DB_TABLE_ORDERS ." set
        starred = ". (int)$this->data['starred'] .",
        unread = ". (int)$this->data['unread'] .",
        order_status_id = ". (int)$this->data['order_status_id'] .",
        customer_id = ". (int)$this->data['customer']['id'] .",
        customer_email = '". database::input($this->data['customer']['email']) ."',
        customer_tax_id = '". database::input($this->data['customer']['tax_id']) ."',
        customer_company = '". database::input($this->data['customer']['company']) ."',
        customer_firstname = '". database::input($this->data['customer']['firstname']) ."',
        customer_lastname = '". database::input($this->data['customer']['lastname']) ."',
        customer_address1 = '". database::input($this->data['customer']['address1']) ."',
        customer_address2 = '". database::input($this->data['customer']['address2']) ."',
        customer_city = '". database::input($this->data['customer']['city']) ."',
        customer_postcode = '". database::input($this->data['customer']['postcode']) ."',
        customer_country_code = '". database::input($this->data['customer']['country_code']) ."',
        customer_zone_code = '". database::input($this->data['customer']['zone_code']) ."',
        customer_phone = '". database::input($this->data['customer']['phone']) ."',
        shipping_company = '". database::input($this->data['customer']['shipping_address']['company']) ."',
        shipping_firstname = '". database::input($this->data['customer']['shipping_address']['firstname']) ."',
        shipping_lastname = '". database::input($this->data['customer']['shipping_address']['lastname']) ."',
        shipping_address1 = '". database::input($this->data['customer']['shipping_address']['address1']) ."',
        shipping_address2 = '". database::input($this->data['customer']['shipping_address']['address2']) ."',
        shipping_city = '". database::input($this->data['customer']['shipping_address']['city']) ."',
        shipping_postcode = '". database::input($this->data['customer']['shipping_address']['postcode']) ."',
        shipping_country_code = '". database::input($this->data['customer']['shipping_address']['country_code']) ."',
        shipping_zone_code = '". database::input($this->data['customer']['shipping_address']['zone_code']) ."',
        shipping_phone = '". database::input($this->data['customer']['shipping_address']['phone']) ."',
        shipping_option_id = '". database::input($this->data['shipping_option']['id']) ."',
        shipping_option_name = '". database::input($this->data['shipping_option']['name']) ."',
        shipping_tracking_id = '". database::input($this->data['shipping_tracking_id']) ."',
        shipping_tracking_url = '". database::input($this->data['shipping_tracking_url']) ."',
        payment_option_id = '". database::input($this->data['payment_option']['id']) ."',
        payment_option_name = '". database::input($this->data['payment_option']['name']) ."',
        payment_transaction_id = '". database::input($this->data['payment_transaction_id']) ."',
        reference = '". database::input($this->data['reference']) ."',
        language_code = '". database::input($this->data['language_code']) ."',
        currency_code = '". database::input($this->data['currency_code']) ."',
        currency_value = ". (float)$this->data['currency_value'] .",
        weight_total = ". (float)$this->data['weight_total'] .",
        weight_class = '". database::input($this->data['weight_class']) ."',
        display_prices_including_tax = ". (int)$this->data['display_prices_including_tax'] .",
        payment_due = ". (float)$this->data['payment_due'] .",
        tax_total = ". (float)$this->data['tax_total'] .",
        public_key = '". database::input($this->data['public_key']) ."',
        date_updated = '". ($this->data['date_updated'] = date('Y-m-d H:i:s')) ."'
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

    // Delete order items
      $previous_order_items_query = database::query(
        "select * from ". DB_TABLE_ORDERS_ITEMS ."
        where order_id = ". (int)$this->data['id'] ."
        and id not in ('". @implode("', '", array_column($this->data['items'], 'id')) ."');"
      );

      while ($previous_order_item = database::fetch($previous_order_items_query)) {
        database::query(
          "delete from ". DB_TABLE_ORDERS_ITEMS ."
          where order_id = ". (int)$this->data['id'] ."
          and id = ". (int)$previous_order_item['id'] ."
          limit 1;"
        );

      // Restock
        if (!empty($previous_order_status['is_sale'])) {
          functions::catalog_stock_adjust($previous_order_item['product_id'], $previous_order_item['option_stock_combination'], $previous_order_item['quantity']);
        }
      }

    // Insert/update order items
      if (!empty($this->data['items'])) {
        foreach (array_keys($this->data['items']) as $key) {
          if (empty($this->data['items'][$key]['id'])) {
            database::query(
              "insert into ". DB_TABLE_ORDERS_ITEMS ."
              (order_id)
              values (". (int)$this->data['id'] .");"
            );
            $this->data['items'][$key]['id'] = database::insert_id();

          // Update purchase count
            if (!empty($this->data['items'][$key]['product_id'])) {
              database::query(
                "update ". DB_TABLE_PRODUCTS ."
                set purchases = purchases + ". (float)$this->data['items'][$key]['quantity'] ."
                where id = ". (int)$this->data['items'][$key]['product_id'] ."
                limit 1;"
              );
            }
          }


          if(!empty(customer::$data['id']) && !empty(customer::$data['vip']) && isset($this->data['items'][$key]['vip_price_standing'])){
              $previous_vip_order_query = database::query(
                  "select * from ". DB_TABLE_VIPLIST ."
                  where product_id = ". (int)$this->data['items'][$key]['product_id'] ." and customer_id = ". (int)customer::$data['id'] .";");
              if(!(database::num_rows($previous_vip_order_query) > 0)){
                  database::query(
                      "insert into ". DB_TABLE_VIPLIST ." (product_id, customer_id) 
                      values (". (int)$this->data['items'][$key]['product_id'] .", ". (int)customer::$data['id'] .");"
                  );
              }
          }
      
        // Get previous quantity
          $previous_order_item_query = database::query(
            "select * from ". DB_TABLE_ORDERS_ITEMS ."
            where id = ". (int)$this->data['items'][$key]['id'] ."
            and order_id = ". (int)$this->data['id'] .";"
          );
          $previous_order_item = database::fetch($previous_order_item_query);

        // Adjust stock
          if (!empty($previous_order_status['is_sale'])) {
            functions::catalog_stock_adjust($previous_order_item['product_id'], $previous_order_item['option_stock_combination'], $previous_order_item['quantity']);
          }
          if (!empty($current_order_status['is_sale'])) {
            functions::catalog_stock_adjust($this->data['items'][$key]['product_id'], $this->data['items'][$key]['option_stock_combination'], -$this->data['items'][$key]['quantity']);
          }

          database::query(
            "update ". DB_TABLE_ORDERS_ITEMS ."
            set product_id = ". (int)$this->data['items'][$key]['product_id'] .",
            option_stock_combination = '". database::input($this->data['items'][$key]['option_stock_combination']) ."',
            options = '". (isset($this->data['items'][$key]['options']) ? database::input(serialize($this->data['items'][$key]['options'])) : '') ."',
            name = '". database::input($this->data['items'][$key]['name']) ."',
            sku = '". database::input($this->data['items'][$key]['sku']) ."',
            gtin = '". database::input($this->data['items'][$key]['gtin']) ."',
            taric = '". database::input($this->data['items'][$key]['taric']) ."',
            quantity = ". (float)$this->data['items'][$key]['quantity'] .",
            price = ". (float)$this->data['items'][$key]['price'] .",
            tax = ". (float)$this->data['items'][$key]['tax'] .",
            weight = ". (float)$this->data['items'][$key]['weight'] .",
            weight_class = '". database::input($this->data['items'][$key]['weight_class']) ."',
            dim_x = ". (float)$this->data['items'][$key]['dim_x'] .",
            dim_y = ". (float)$this->data['items'][$key]['dim_y'] .",
            dim_z = ". (float)$this->data['items'][$key]['dim_z'] .",
            dim_class = '". database::input($this->data['items'][$key]['dim_class']) ."'
            where order_id = ". (int)$this->data['id'] ."
            and id = ". (int)$this->data['items'][$key]['id'] ."
            limit 1;"
          );
        }
      }

    // Delete order total items
      database::query(
        "delete from ". DB_TABLE_ORDERS_TOTALS ."
        where order_id = ". (int)$this->data['id'] ."
        and id not in ('". @implode("', '", array_column($this->data['order_total'], 'id')) ."');"
      );

    // Insert/update order total
      if (!empty($this->data['order_total'])) {
        $i = 0;
        foreach (array_keys($this->data['order_total']) as $key) {
          if (empty($this->data['order_total'][$key]['id'])) {
            database::query(
              "insert into ". DB_TABLE_ORDERS_TOTALS ."
              (order_id)
              values (". (int)$this->data['id'] .");"
            );
            $this->data['order_total'][$key]['id'] = database::insert_id();
          }
          database::query(
            "update ". DB_TABLE_ORDERS_TOTALS ."
            set title = '". database::input($this->data['order_total'][$key]['title']) ."',
            module_id = '". database::input($this->data['order_total'][$key]['module_id']) ."',
            value = '". (float)$this->data['order_total'][$key]['value'] ."',
            tax = '". (float)$this->data['order_total'][$key]['tax'] ."',
            calculate = '". (empty($this->data['order_total'][$key]['calculate']) ? 0 : 1) ."',
            priority = '". database::input(++$i) ."'
            where order_id = ". (int)$this->data['id'] ."
            and id = ". (int)$this->data['order_total'][$key]['id'] ."
            limit 1;"
          );
        }
      }

    // Delete comments
      database::query(
        "delete from ". DB_TABLE_ORDERS_COMMENTS ."
        where order_id = ". (int)$this->data['id'] ."
        and id not in ('". @implode("', '", array_column($this->data['comments'], 'id')) ."');"
      );
      
      

    // Insert/update comments
      if (!empty($this->data['comments'])) {
          
          
        
        $notify_comments = array();

        foreach (array_keys($this->data['comments']) as $key) {

          if (empty($this->data['comments'][$key]['author'])) $this->data['comments'][$key]['author'] = 'system';

          if (empty($this->data['comments'][$key]['id'])) {
            database::query(
              "insert into ". DB_TABLE_ORDERS_COMMENTS ."
              (order_id, date_created)
              values (". (int)$this->data['id'] .", '". ($this->data['comments'][$key]['date_created'] = date('Y-m-d H:i:s')) ."');"
            );
            $this->data['comments'][$key]['id'] = database::insert_id();

            if ($this->data['comments'][$key]['author'] == 'staff' && !empty($this->data['comments'][$key]['notify']) && empty($this->data['comments'][$key]['hidden'])) {
              $notify_comments[] = $this->data['comments'][$key];
            }
          }

          database::query(
            "update ". DB_TABLE_ORDERS_COMMENTS ."
            set author = '". (!empty($this->data['comments'][$key]['author']) ? database::input($this->data['comments'][$key]['author']) : 'system') ."',
              text = '". database::input($this->data['comments'][$key]['text']) ."',
              hidden = '". (!empty($this->data['comments'][$key]['hidden']) ? 1 : 0) ."'
            where order_id = ". (int)$this->data['id'] ."
            and id = ". (int)$this->data['comments'][$key]['id'] ."
            limit 1;"
          );
        }

        if (!empty($notify_comments)) {
            

          $subject = '['. language::translate('title_order', 'Order') .' #'. $this->data['id'] .'] ' . language::translate('title_new_comments_added', 'New Comments Added', $this->data['language_code']);

          $message = language::translate('text_new_comments_added_to_your_order', 'New comments added to your order', $this->data['language_code']) . ":\r\n\r\n";
          foreach ($notify_comments as $comment) {
            $message .= language::strftime(language::$selected['format_datetime'], strtotime($comment['date_created'])) ." ??? ". trim($comment['text']) . "\r\n\r\n";
          }

          $email = new ent_email();
          $email->add_recipient($this->data['customer']['email'], $this->data['customer']['firstname'] .' '. $this->data['customer']['lastname'])
                ->set_subject($subject)
                ->add_body($message)
                ->send();
        }
      }

    // Send order status email notification
      if (!empty($this->previous) && ($this->data['order_status_id'] != $this->previous['order_status_id'])) {
          
        if (!empty($current_order_status['notify'])) {
          $this->send_email_notification();
        }
      }

      $order_modules = new mod_order();
      $order_modules->update($this->data);

      $this->previous = $this->data;

      cache::clear_cache('order');
      cache::clear_cache('category');
      cache::clear_cache('manufacturer');
      cache::clear_cache('products');
    }

    public function refresh_total() {
      $this->data['subtotal'] = array('amount' => 0, 'tax' => 0);
      $this->data['payment_due'] = 0;
      $this->data['tax_total'] = 0;
      $this->data['weight_total'] = 0;

      foreach ($this->data['items'] as $item) {
        $this->data['subtotal']['amount'] += $item['price'] * $item['quantity'];
        $this->data['subtotal']['tax'] += $item['tax'] * $item['quantity'];
        $this->data['payment_due'] += ($item['price'] + $item['tax']) * $item['quantity'];
        $this->data['tax_total'] += $item['tax'] * $item['quantity'];
        $this->data['weight_total'] += weight::convert($item['weight'], $item['weight_class'], $this->data['weight_class']) * $item['quantity'];
      }

      foreach ($this->data['order_total'] as $i => $row) {
        if ($row['module_id'] == 'ot_subtotal') {
          $this->data['order_total'][$i]['value'] = $this->data['subtotal']['amount'];
          $this->data['order_total'][$i]['tax'] = $this->data['subtotal']['tax'];
          break;
        }
      }

      foreach ($this->data['order_total'] as $row) {
        if (empty($row['calculate'])) continue;
        $this->data['payment_due'] += ($row['value'] + $row['tax']);
        $this->data['tax_total'] += $row['tax'];
      }
    }

    public function add_item($item) {

      $fields = array(
        'product_id',
        'options',
        'option_stock_combination',

          'vip_price_standing',
      
        'name',
        'sku',
        'gtin',
        'taric',
        'price',
        'tax',
        'quantity',
        'weight',
        'weight_class',
        'dim_x',
        'dim_y',
        'dim_z',
        'dim_class',
        'error',
      );

      $i = 1;
      while (isset($this->data['items']['new_'.$i])) $i++;
      $item_key = 'new_'.$i;

      $this->data['items']['new_'.$i]['id'] = null;

      foreach ($fields as $field) {
        $this->data['items']['new_'.$i][$field] = isset($item[$field]) ? $item[$field] : null;
      }

      $this->data['subtotal']['amount'] += $item['price'] * $item['quantity'];
      $this->data['subtotal']['tax'] += $item['tax'] * $item['quantity'];
      $this->data['payment_due'] += ($item['price'] + $item['tax']) * $item['quantity'];
      $this->data['tax_total'] += $item['tax'] * $item['quantity'];
      $this->data['weight_total'] += weight::convert($item['weight'], $item['weight_class'], $this->data['weight_class']) * $item['quantity'];
    }

    public function add_ot_row($row) {

      $row = array(
        'id' => 0,
        'module_id' => $row['id'],
        'title' =>  $row['title'],
        'value' => $row['value'],
        'tax' => $row['tax'],
        'calculate' => !empty($row['calculate']) ? 1 : 0,
      );

      $i = 1;
      while (isset($this->data['order_total']['new_'.$i])) $i++;
      $this->data['order_total']['new_'.$i] = $row;

      if (!empty($row['calculate'])) {
        $this->data['payment_due'] += $row['value'] + $row['tax'];
        $this->data['tax_total'] += $row['tax'];
      }
    }

    public function validate(&$shipping = null, &$payment = null) {

    // Validate items
      if (empty($this->data['items'])) return language::translate('error_order_missing_items', 'The order does not contain any items');

      foreach ($this->data['items'] as $item) {
        if (!empty($item['error'])) return language::translate('error_cart_contains_errors', 'Your cart contains errors');
      }

    // Validate customer details
      try {
        if (empty($this->data['customer']['firstname'])) throw new Exception(language::translate('error_missing_firstname', 'You must enter a first name.'));
        if (empty($this->data['customer']['lastname'])) throw new Exception(language::translate('error_missing_lastname', 'You must enter a last name.'));
        if (empty($this->data['customer']['address1'])) throw new Exception(language::translate('error_missing_address1', 'You must enter an address.'));
        if (empty($this->data['customer']['city'])) throw new Exception(language::translate('error_missing_city', 'You must enter a city.'));
        if (empty($this->data['customer']['country_code'])) throw new Exception(language::translate('error_missing_country', 'You must select a country.'));
        if (empty($this->data['customer']['email'])) throw new Exception(language::translate('error_missing_email', 'You must enter an email address.'));
        if (empty($this->data['customer']['phone'])) throw new Exception(language::translate('error_missing_phone', 'You must enter a phone number.'));

        if (!functions::validate_email($this->data['customer']['email'])) throw new Exception(language::translate('error_invalid_email_address', 'Invalid email address'));

        if (reference::country($this->data['customer']['country_code'])->postcode_format) {
          if (!empty($this->data['customer']['postcode'])) {
            if (!preg_match('#'. reference::country($this->data['customer']['country_code'])->postcode_format .'#i', $this->data['customer']['postcode'])) {
              throw new Exception(language::translate('error_invalid_postcode_format', 'Invalid postcode format.'));
            }
          } else {
            throw new Exception(language::translate('error_missing_postcode', 'You must enter a postcode.'));
          }
        }

        if (reference::country($this->data['customer']['country_code'])->zones) {
          if (empty($this->data['customer']['zone_code']) && reference::country($this->data['customer']['country_code'])->zones) throw new Exception(language::translate('error_missing_zone', 'You must select a zone.'));
        }

        if (empty($this->data['customer']['id'])) {
          $customer_query = database::query(
            "select id from ". DB_TABLE_CUSTOMERS ."
            where email = '". database::input($this->data['customer']['email']) ."'
            and status = 0
            limit 1;"
          );

          if (database::num_rows($customer_query)) {
            throw new Exception(language::translate('error_customer_account_is_disabled', 'The customer account is disabled'));
          }
        }

      } catch (Exception $e) {
        return language::translate('title_customer_details', 'Customer Details') .': '. $e->getMessage();
      }

      try {
        if (!empty($this->data['customer']['different_shipping_address'])) {
          if (empty($this->data['customer']['shipping_address']['firstname'])) throw new Exception(language::translate('error_missing_firstname', 'You must enter a first name.'));
          if (empty($this->data['customer']['shipping_address']['lastname'])) throw new Exception(language::translate('error_missing_lastname', 'You must enter a last name.'));
          if (empty($this->data['customer']['shipping_address']['address1'])) throw new Exception(language::translate('error_missing_address1', 'You must enter an address.'));
          if (empty($this->data['customer']['shipping_address']['city'])) throw new Exception(language::translate('error_missing_city', 'You must enter a city.'));
          if (empty($this->data['customer']['shipping_address']['country_code'])) throw new Exception(language::translate('error_missing_country', 'You must select a country.'));

          if (reference::country($this->data['customer']['shipping_address']['country_code'])->postcode_format) {
            if (!empty($this->data['customer']['shipping_address']['postcode'])) {
              if (!preg_match('#'. reference::country($this->data['customer']['shipping_address']['country_code'])->postcode_format .'#i', $this->data['customer']['shipping_address']['postcode'])) {
                throw new Exception(language::translate('error_invalid_postcode_format', 'Invalid postcode format.'));
              }
            } else {
              throw new Exception(language::translate('error_missing_postcode', 'You must enter a postcode.'));
            }
          }

          if (reference::country($this->data['customer']['country_code'])->zones) {
            if (empty($this->data['customer']['shipping_address']['zone_code']) && reference::country($this->data['customer']['shipping_address']['country_code'])->zones) return language::translate('error_missing_zone', 'You must select a zone.');
          }
        }

      } catch (Exception $e) {
        return language::translate('title_shipping_address', 'Shipping Address') .': '. $e->getMessage();
      }

    // Additional customer validation
      $mod_customer = new mod_customer();
      $result = $mod_customer->validate($this->data['customer']);

      if (!empty($result['error'])) {
        return $result['error'];
      }

    // Validate shipping option
      if (!empty($shipping->modules) && count($shipping->options($this->data['items'], $this->data['currency_code'], $this->data['customer']))) {
        if (empty($this->data['shipping_option']['id'])) {
          return language::translate('error_no_shipping_method_selected', 'No shipping method selected');
        } else {
          list($module_id, $option_id) = explode(':', $this->data['shipping_option']['id']);
          if (empty($shipping->data['options'][$module_id]['options'][$option_id])) {
            return language::translate('error_invalid_shipping_method_selected', 'Invalid shipping method selected');
          }
          if (!empty($shipping->data['options'][$module_id]['options'][$option_id]['error'])) {
            return language::translate('error_shipping_method_contains_error', 'The selected shipping method contains an error');
          }
        }
      }

    // Validate payment option
      if (!empty($payment->modules) && count($payment->options($this->data['items'], $this->data['currency_code'], $this->data['customer']))) {
        if (empty($this->data['payment_option']['id'])) {
          return language::translate('error_no_payment_method_selected', 'No payment method selected');
        } else {
          list($module_id, $option_id) = explode(':', $this->data['payment_option']['id']);
          if (empty($payment->data['options'][$module_id]['options'][$option_id])) {
            return language::translate('error_invalid_payment_method_selected', 'Invalid payment method selected');
          }
          if (!empty($payment->data['options'][$module_id]['options'][$option_id]['error'])) {
            return language::translate('error_payment_method_contains_error', 'The selected payment method contains an error');
          }
        }
      }

// MINIMUM ORDER VALUE
if (!empty(customer::$data['notes'] == 2)) {
foreach ($this->data['order_total'] as $row) {
  if ($row['module_id'] == 'ot_subtotal') {
    if ($row['value'] < 1500) { // Store currency
      return 'Minimum order for wholesale subtotal is RM 1500';
    }
  }
}
}

// MINIMUM ORDER VALUE
if (!empty(customer::$data['notes'] == 3)) {
foreach ($this->data['order_total'] as $row) {
  if ($row['module_id'] == 'ot_subtotal') {
    if ($row['value'] < 300) { // Store currency
      return 'Minimum order subtotal is 300';
    }
  }
}
}



    // Additional order validation

            // MINIMUM ORDER VALUE
            if (!empty(customer::$data['wholesale_subtotal'] == 1)) {
            foreach ($this->data['order_total'] as $row) {
              if ($row['module_id'] == 'ot_subtotal') {
               if ($row['value'] < 3000) { // Store currency
                 return 'Minimum order for wholesale subtotal is RM 3000';
               }
              }
             }
            }

            // MINIMUM ORDER VALUE
            if (!empty(customer::$data['wholesale_subtotal'] == 2)) {
            foreach ($this->data['order_total'] as $row) {
              if ($row['module_id'] == 'ot_subtotal') {
               if ($row['value'] < 1500) { // Store currency
                 return 'Minimum order for wholesale subtotal is RM 1500';
               }
              }
             }
            }
            
            // STOP CUSTOMER FROM BUYING
            if (!empty(customer::$data['wholesale_subtotal'] == 3)) {
            foreach ($this->data['order_total'] as $row) {
              if ($row['module_id'] == 'ot_subtotal') {
               if ($row['value'] < 100000) { // Store currency
                 return 'Unfortunately you had reach the maximum intallment plan order.';
               }
              }
             }
            }    
            
            // BANNED CUSTOMER 
            if (!empty(customer::$data['wholesale_subtotal'] == 9)) {
            foreach ($this->data['order_total'] as $row) {
              if ($row['module_id'] == 'ot_subtotal') {
               if ($row['value'] < 10000000) { // Store currency
                 return 'Unfortunately your aaccount banned.';
               }
              }
             }
            }             
      
      $mod_order = new mod_order();
      $result = $mod_order->validate($this);

      if (!empty($result['error'])) {
        return $result['error'];
      }

      return false;
    }

    public function email_order_copy($recipient, $bccs=array(), $language_code='') {

      if (empty($recipient)) return;
      if (empty($language_code)) $language_code = $this->data['language_code'];
      if (empty($this->data['order_status_id'])) return;

      $order_status = reference::order_status($this->data['order_status_id'], $language_code);

      $aliases = array(
        '%order_id' => $this->data['id'],
        '%firstname' => $this->data['customer']['firstname'],
        '%lastname' => $this->data['customer']['lastname'],
        '%billing_address' => functions::format_address($this->data['customer']),
        '%payment_transaction_id' => !empty($this->data['payment_transaction_id']) ? $this->data['payment_transaction_id'] : '-',
        '%shipping_address' => functions::format_address($this->data['customer']['shipping_address']),
        '%shipping_tracking_id' => !empty($this->data['shipping_tracking_id']) ? $this->data['shipping_tracking_id'] : '-',
        '%shipping_tracking_url' => !empty($this->data['shipping_tracking_url']) ? $this->data['shipping_tracking_url'] : '',
        '%order_items' => null,
        '%payment_due' => currency::format($this->data['payment_due'], true, $this->data['currency_code'], $this->data['currency_value']),
        '%order_copy_url' => document::ilink('order', array('order_id' => $this->data['id'], 'public_key' => $this->data['public_key']), false, array(), $language_code),
        '%order_status' => !empty($order_status) ? $order_status->name : null,
        '%store_name' => settings::get('store_name'),
        '%store_url' => document::ilink('', array(), false, array(), $language_code),
      );


                list($product_image_width, $product_image_height) = functions::image_scale_by_width(180, settings::get('product_image_ratio'));
                $aliases['%order_items'] = '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%; background-color: #e8c48675;">
                    <thead>
                    <tr style="background-image: url(https://ittoysline.com/images/customize/Gold%20Brush%20Table.png);" >
                        <th style="color:black; padding: 10px 12px; border-left: 1px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Product Image</th>
                        <th style="color:black; padding: 10px 12px; border-left: 0px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Description / Information</th>
                    </tr>
                    </thead><tbody>';
                
      foreach ($this->data['items'] as $item) {

        if (!empty($item['product_id'])) {
          
                $product = new ent_product($item['product_id']);
                

          $options = array();
          if (!empty($item['options'])) {
            foreach ($item['options'] as $k => $v) {
              $options[] = $k .': '. $v;
            }
          }

          
                $image = current($product->data['images']);
                $image_url = document::href_link(WS_DIR_APP.functions::image_thumbnail(FS_DIR_APP . 'images/' . $image['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping')));
                $img = '<img src="'. $image_url .'" />';
                $aliases['%order_items'] .= '
                <tr>
                  <td style="padding: 0px 0px; text-align: center; color: #000000; border-left: 1px solid; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid" >'. $img .'</td>
                  <td style="padding: 1px 0px 26px; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid; color:black ">
                
                    <table auto="" border="0" cellpadding="1" cellspacing="1" style="background-color: transparent;  text-align: left;">
                      <tbody>
                       <tr align="left">
                         <td width="auto">
                            <div class="separator" style="clear: both; text-align: left; ">
                               <span style="color: black; font-size:11pt">&nbsp;Name</span>
                         </td>
                        
                         <td><span style="color: black; font-size:11pt">:&nbsp;</span></td>
                         <td valign="top">
                            <span style="color: black; font-size:11pt"> ' . $product->data['short_description'][language::$selected['code']] . ' </span>
                         </td>    
                       </tr>
                       <tr align="left">
                        <td valign="top">
                           <span style="color: black; font-size:11pt">&nbsp;Barcode</span>
                           </td>
                        <td valign="top">
                            <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . $item['sku'] . ' 
                        </td>
                      </tr>                

                      <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Price</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . currency::format($item['price'], true, $this->data['currency_code'], $this->data['currency_value']) . ' 
                        </td>
                      </tr>    

                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Quantity</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . (float)$item['quantity'] . ' pcs 
                        </td>
                     </tr>
                     <br>
                
                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Total</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:10pt"> ' . currency::format((float)$item['price'] * (float)$item['quantity'], true,  $this->data['currency_code'], $this->data['currency_value']) . ' </td>
                     </tr>                
                
                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Status</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:10pt"> ' . $product->data['delivery_status_info'] . ' </td>
                     </tr>
                     </tbody>
                     </table>
                </td>
                </tr>';
                
                

        } else {
          
                $aliases['%order_items'] .= '<tr><td></td><td>'.(float)$item['quantity'] .' x '. $item['name'] . (!empty($options) ? ' ('. implode(', ', $options) .')' : '') . "</td></tr>\r\n";
                
        }
      }

      $aliases['%order_items'] = trim($aliases['%order_items']);

                $aliases['%order_items'] .= '
                </tbody>
                </table>
                <table style="width:100%; border-left: 1px solid; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid; background-image: url(https://ittoysline.com/images/customize/Gold%20Brush%20Table.png); text-align:left">
                <tbody>
                <tr align="left">
                  <td width="100px">
                     <div class="separator" style="clear: both; text-align: left; ">
                     <span style="color: black; font-size:11pt">Subtotal</span>
                  </td>
                  <td max-width="1px">
                     <span style="color: black; font-size:11pt">:</span>
                  </td>
                  <td valign="top"; text-align: left; max-width="80px">
                     <span style="color: black; text-align: left; font-size:11pt"> ' . currency::format($this->data['subtotal']['amount'], true, $this->data['currency_code'], $this->data['currency_value']) . ' </span>
                  </td>     
                </tr>
                <tr align="left">
                  <td valign="top"; width="100px">
                  <span style="color: black; font-size:11pt">Shipping</span>
                  </td>
                  <td valign="top"; max-width="1px">
                     <span style="color: black; font-size:11pt">:</span>
                  </td>
                  <td valign="top" max-width="80px"; style="color: black; text-align: left; font-size:11pt"> ' . currency::format($this->data['payment_due'] - ($this->data['subtotal']['amount']), true, $this->data['currency_code'], $this->data['currency_value']) . ' 
                  </td>
                </tr>                

                <tr align="left">
                  <td valign="top"; width="100px">
                    <span style="color: black; font-size:11pt">Discount</span></td>
                  <td valign="top"; max-width="1px">
                    <span style="color: black; font-size:11pt">:</span></td>
                  <td valign="top" max-width="80px"; style="color: black; text-align: left; font-size:11pt"> ' . currency::format($this->data['order_discount_code'], true, $this->data['currency_code'], $this->data['currency_value']) . ' 
                  </td>
                </tr>    

                <tr align="left">
                   <td valign="top"; width="100px">
                     <span style="color: black; font-size:11pt">Grand Total</span></td>
                   <td valign="top"; max-width="1px">
                     <span style="color: black; font-size:11pt">:</span></td>
                   <td valign="top" max-width="80px"; style="color: black; text-align: left; font-size:11pt"> ' . currency::format($this->data['payment_due'], true, $this->data['currency_code'], $this->data['currency_value']) . ' </td>
                </tr>
                </tbody>
                </table>
                ';
                
                

      
                $subject = '['. language::translate('title_order', 'Order', $language_code) .' No: '. $this->data['id'] .'] '. language::translate('title_order_confirmation', 'Order Confirmation', $language_code);
                $aliases['%order_subject'] = $subject;
                

      $message = "Thank you for your purchase!\r\n\r\n"
               . "Your order #%order_id has successfully been created with a total of %payment_due for the following ordered items:\r\n\r\n"
               . "%order_items\r\n\r\n"
               . "A printable order copy is available here:\r\n"
               . "%order_copy_url\r\n\r\n"
               . "Regards,\r\n"
               . "%store_name\r\n"
               . "%store_url\r\n";

      $message = strtr(language::translate('email_order_confirmation', $message, $language_code), $aliases);

      $email = new ent_email();

      if (!empty($bccs)) {
        foreach ($bccs as $bcc) {
          $email->add_bcc($bcc);
        }
      }

      
                $email->add_recipient($recipient)
                ->set_subject($subject)
                ->add_body($message, true)
                ->send();
                



    }

    public function send_email_notification() {

      if (empty($this->data['order_status_id'])) return;
      
      $order_status = reference::order_status($this->data['order_status_id'], $this->data['language_code']);

      $aliases = array(
        '%order_id' => $this->data['id'],
        '%firstname' => $this->data['customer']['firstname'],
        '%lastname' => $this->data['customer']['lastname'],

                '%shipping_company' => ($this->data['customer']['shipping_address']['company']),
                '%shipping_address1' => ($this->data['customer']['shipping_address']['address1']),
                '%shipping_address2' => ($this->data['customer']['shipping_address']['address2']),
                '%shipping_postcode' => ($this->data['customer']['shipping_address']['postcode']),
                '%shipping_city' => ($this->data['customer']['shipping_address']['city']),
                '%shipping_phone' => $this->data['customer']['shipping_address']['phone'],
                '%shipping_zone_code' => ($this->data['customer']['shipping_address']['zone_code']),
                '%shipping_country_code' => ($this->data['customer']['shipping_address']['country_code']),
                '%shipping_country_name' => reference::country($this->data['customer']['shipping_address']['country_code'])->name,

                '%deposit' => currency::format($this->data['payment_due']* 0.5, true, $this->data['currency_code'], $this->data['currency_value']),
                '%subtotal' => currency::format($this->data['subtotal']['amount'] += $item['price'] * $item['quantity'], true, $this->data['currency_code'], $this->data['currency_value']),
                '%shipping_fee' => currency::format($this->data['order_shipping_fee'], true, $this->data['currency_code'], $this->data['currency_value']),
                '%grand_total' => currency::format($this->data['order_original_grandtotal'], true, $this->data['currency_code'], $this->data['currency_value']),
                
                
                '%payment_received' => currency::format($this->data['payment_received'], true, $this->data['currency_code'], $this->data['currency_value']),

                '%order_discount_code' => currency::format($this->data['order_discount_code'], true, $this->data['currency_code'], $this->data['currency_value']),
                '%order_customer_discount' => currency::format($this->data['order_customer_discount'], true, $this->data['currency_code'], $this->data['currency_value']),

                
        '%billing_address' => functions::format_address($this->data['customer']),
        '%payment_transaction_id' => !empty($this->data['payment_transaction_id']) ? $this->data['payment_transaction_id'] : '-',
        '%shipping_address' => functions::format_address($this->data['customer']['shipping_address']),
        '%shipping_tracking_id' => !empty($this->data['shipping_tracking_id']) ? $this->data['shipping_tracking_id'] : '-',
        '%shipping_tracking_url' => !empty($this->data['shipping_tracking_url']) ? $this->data['shipping_tracking_url'] : '',
        '%order_items' => null,
        '%payment_due' => currency::format($this->data['payment_due'], true, $this->data['currency_code'], $this->data['currency_value']),
        '%order_copy_url' => document::ilink('order', array('order_id' => $this->data['id'], 'public_key' => $this->data['public_key']), false, array(), $this->data['language_code']),
        '%order_status' => $order_status->name,
        '%store_name' => settings::get('store_name'),
        '%store_url' => document::ilink('', array(), false, array(), $this->data['language_code']),
      );


                list($product_image_width, $product_image_height) = functions::image_scale_by_width(180, settings::get('product_image_ratio'));
                $aliases['%order_items'] = '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%; text-align:center; background-color: #e8c48675;">
                    <thead>
                    <tr style="background-image: url(https://ittoysline.com/images/customize/Gold%20Brush%20Table.png);" >
                        <th style="color:black; padding: 10px 12px; border-left: 1px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Product Image</th>
                        <th style="color:black; padding: 10px 12px; border-left: 0px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Description / Information</th>
                    </tr>
                    </thead><tbody>';
                
      foreach ($this->data['items'] as $item) {

        if (!empty($item['product_id'])) {
          
                $product = new ent_product($item['product_id']);
                

          $options = array();
          if (!empty($item['options'])) {
            foreach ($item['options'] as $k => $v) {
              $options[] = $k .': '. $v;
            }
          }

          
                $image = current($product->data['images']);
                $image_url = document::href_link(WS_DIR_APP.functions::image_thumbnail(FS_DIR_APP . 'images/' . $image['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping')));
                $img = '<img src="'. $image_url .'" />';
                $aliases['%order_items'] .= '
                <tr>
                  <td style="padding: 0px 0px; text-align: center; color: #000000; border-left: 1px solid; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid" >'. $img .'</td>
                  <td style="padding: 1px 0px 26px; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid; color:black ">
                
                    <table auto="" border="0" cellpadding="1" cellspacing="1" style="background-color: transparent;  text-align: left;">
                      <tbody>
                       <tr align="left">
                         <td width="auto">
                            <div class="separator" style="clear: both; text-align: left; ">
                               <span style="color: black; font-size:11pt">&nbsp;Name</span>
                         </td>
                        
                         <td><span style="color: black; font-size:11pt">:&nbsp;</span></td>
                         <td valign="top">
                            <span style="color: black; font-size:11pt"> ' . $product->data['short_description'][language::$selected['code']] . ' </span>
                         </td>    
                       </tr>
                       <tr align="left">
                        <td valign="top">
                           <span style="color: black; font-size:11pt">&nbsp;Barcode</span>
                           </td>
                        <td valign="top">
                            <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . $item['sku'] . ' 
                        </td>
                      </tr>                

                      <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Price</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . currency::format($item['price'], true, $this->data['currency_code'], $this->data['currency_value']) . ' 
                        </td>
                      </tr>    

                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Quantity</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:11pt"> ' . (float)$item['quantity'] . ' pcs 
                        </td>
                     </tr>
                     <br>
                
                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Total</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:10pt"> ' . currency::format((float)$item['price'] * (float)$item['quantity'], true,  $this->data['currency_code'], $this->data['currency_value']) . ' </td>
                     </tr>                
                
                     <tr align="left">
                        <td valign="top">
                          <span style="color: black; font-size:11pt">&nbsp;Status</span>
                        </td>
                        <td valign="top">
                          <span style="color: black; font-size:11pt">:&nbsp;</span>
                        </td>
                        <td valign="top" width="max" style="color: black; font-size:10pt"> ' . $product->data['delivery_status_info'] . ' </td>
                     </tr>
                     </tbody>
                     </table>
                </td>
                </tr>';
                
                

        } else {
          
                $aliases['%order_items'] .= '<tr><td></td><td>'.(float)$item['quantity'] .' x '. $item['name'] . (!empty($options) ? ' ('. implode(', ', $options) .')' : '') . "</td></tr>\r\n";
                
        }
      }

      $subject = strtr($order_status->email_subject, $aliases);

                $aliases['%order_subject'] = $subject;
                
      $message = strtr($order_status->email_message, $aliases);
      
      
      if (empty($subject)) $subject = '['. language::translate('title_order', 'Order', $this->data['language_code']) .' #'. $this->data['id'] .'] '. $order_status->name;
      if (empty($message)) $message = strtr(language::translate('text_order_status_changed_to_new_status', 'Order status changed to %new_status', $this->data['language_code']), array('%new_status' => $order_status->name));

      $email = new ent_email();
      $email->add_recipient($this->data['customer']['email'], $this->data['customer']['firstname'] .' '. $this->data['customer']['lastname'])
            ->set_subject($subject)
            ->add_body($message, true)
            ->send();
    }

    public function delete() {

      if (empty($this->data['id'])) return;

      $order_modules = new mod_order();
      $order_modules->delete($this->previous);

    // Empty order first..
      $this->data['items'] = array();
      $this->data['order_total'] = array();
      $this->refresh_total();
      $this->save();

    // ..then delete
      database::query(
        "delete from ". DB_TABLE_ORDERS ."
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $this->reset();

      cache::clear_cache('order');
      cache::clear_cache('category');
      cache::clear_cache('manufacturer');
      cache::clear_cache('products');
    }
  }
