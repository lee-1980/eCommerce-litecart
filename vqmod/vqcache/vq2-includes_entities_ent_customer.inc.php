<?php

  class ent_customer {
    public $data;
    public $previous;

    public function __construct($customer_id=null) {

      if ($customer_id !== null) {
        $this->load($customer_id);
      } else {
        $this->reset();
      }
    }

    public function reset() {

      $this->data = array();

      $fields_query = database::query(
        "show fields from ". DB_TABLE_CUSTOMERS .";"
      );

      while ($field = database::fetch($fields_query)) {
        if (preg_match('#^shipping_(.*)$#', $field['Field'], $matches)) {
          $this->data['shipping_address'][$matches[1]] = '';
        } else {
          $this->data[$field['Field']] = null;
        }
      }

      $this->data['status'] = 1;

      $this->previous = $this->data;
    }

    public function load($customer_id) {
      
      if (!preg_match('#^[0-9]+$#', $customer_id)) throw new Exception('Invalid customer (ID: '. $customer_id .')');

      $this->reset();

      $customer_query = database::query(
        "select * from ". DB_TABLE_CUSTOMERS ."
        where id = ". (int)$customer_id ."
        limit 1;"
      );

      if ($customer = database::fetch($customer_query)) {
        $this->data = array_replace($this->data, array_intersect_key($customer, $this->data));
      } else {
        throw new Exception('Could not find customer (ID: '. (int)$customer_id .') in database.');
      }

      foreach ($customer as $field => $value) {
        if (preg_match('#^shipping_(.*)$#', $field, $matches)) {
          unset($this->data['shipping_'.$matches[1]]);
          $this->data['shipping_address'][$matches[1]] = $value;
        }
      }

      if (empty($this->data['different_shipping_address'])) {
        foreach (array_keys($this->data['shipping_address']) as $key) {
          $this->data['shipping_address'][$key] = null;
        }
        $this->data['shipping_address']['country_code'] = $this->data['country_code'];
        $this->data['shipping_address']['zone_code'] = $this->data['zone_code'];
      }

      $this->previous = $this->data;
    }

    public function save() {

      if (!empty($this->data['id'])) {
        $old_customer = new ent_customer($this->data['id']);
      }
      

      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_CUSTOMERS ."
          (email, date_created)
          values ('". database::input($this->data['email']) ."', '". ($this->data['date_created'] = date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = database::insert_id();

        if (!empty($this->data['email'])) {
          database::query(
            "update ". DB_TABLE_ORDERS ."
            set customer_id = ". (int)$this->data['id'] ."
            where customer_email = '". database::input($this->data['email']) ."';"
          );
        }
      }

      database::query(
        "update ". DB_TABLE_CUSTOMERS ."
        set
          code = '". database::input($this->data['code']) ."',
          status = '". (!empty($this->data['status']) ? '1' : '0') ."',

          customer_group_id = ". (int)$this->data['customer_group_id'] .",
      
          email = '". database::input($this->data['email']) ."',
          tax_id = '". database::input($this->data['tax_id']) ."',
          company = '". database::input($this->data['company']) ."',
          firstname = '". database::input($this->data['firstname']) ."',
          lastname = '". database::input($this->data['lastname']) ."',

          date_valid_from = '". database::input($this->data['date_valid_from']) ."',
          date_valid_to = '". database::input($this->data['date_valid_to']) ."',
          discount_code_date_valid_from = '". database::input($this->data['discount_code_date_valid_from']) ."',
          discount_code_date_valid_to = '". database::input($this->data['discount_code_date_valid_to']) ."',
      
          address1 = '". database::input($this->data['address1']) ."',
          address2 = '". database::input($this->data['address2']) ."',
          postcode = '". database::input($this->data['postcode']) ."',
          city = '". database::input($this->data['city']) ."',
          country_code = '". database::input($this->data['country_code']) ."',
          zone_code = '". database::input($this->data['zone_code']) ."',
          phone = '". database::input($this->data['phone']) ."',
          different_shipping_address = '". (!empty($this->data['different_shipping_address']) ? '1' : '0') ."',
          shipping_company = '". database::input($this->data['shipping_address']['company']) ."',
          shipping_firstname = '". database::input($this->data['shipping_address']['firstname']) ."',
          shipping_lastname = '". database::input($this->data['shipping_address']['lastname']) ."',
          shipping_address1 = '". database::input($this->data['shipping_address']['address1']) ."',
          shipping_address2 = '". database::input($this->data['shipping_address']['address2']) ."',
          shipping_postcode = '". database::input($this->data['shipping_address']['postcode']) ."',
          shipping_city = '". database::input($this->data['shipping_address']['city']) ."',
          shipping_country_code = '". database::input($this->data['shipping_address']['country_code']) ."',
          shipping_zone_code = '". database::input($this->data['shipping_address']['zone_code']) ."',
          shipping_phone = '". database::input($this->data['shipping_address']['phone']) ."',
          newsletter = '". (!empty($this->data['newsletter']) ? '1' : '0') ."',

          discount = '". (float)$this->data['discount'] ."',
      
          notes = '". database::input($this->data['notes']) ."',

        forbidden = '". (!empty($this->data['forbidden']) ? '1' : '0') ."',
        disable_default_price = '". (!empty($this->data['disable_default_price']) ? '1' : '0') ."',
        disable_guest_price = '". (!empty($this->data['disable_guest_price']) ? '1' : '0') ."',
        enable_wholesale_price = '". (!empty($this->data['enable_wholesale_price']) ? '1' : '0') ."',
        disable_wholesale_price = '". (!empty($this->data['disable_wholesale_price']) ? '1' : '0') ."',
        disable_shipping_module = '". (!empty($this->data['disable_shipping_module']) && $this->data['disable_shipping_module'] === '1' ? '1' : '0') ."',
        discount_code_info = '". database::input($this->data['discount_code_info']) ."',
        attempts = '". database::input($this->data['attempts']) ."',
        wholesale_subtotal = '". database::input($this->data['wholesale_subtotal']) ."',
        genuine = '". database::input($this->data['genuine']) ."',
        enable_quantity_price = '". database::input($this->data['enable_quantity_price']) ."', 
        enable_hide_product = '". database::input($this->data['enable_hide_product']) ."', 
        international = '". database::input($this->data['international']) ."', 
        vip = '". database::input($this->data['vip']) ."', 
      
          date_updated = '". ($this->data['date_updated'] = date('Y-m-d H:i:s')) ."'
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $customer_modules = new mod_customer();

      if (!empty($this->previous['id'])) {
        $customer_modules->update($this->data, $this->previous);

      if (isset($old_customer->data['status']) && empty($old_customer->data['status']) && !empty($this->data['status'])) {
        $aliases = array(
          '%customer_id' => $this->data['id'],
          '%customer_email' => $this->data['email'],
          '%customer_name' => !empty($this->data['company']) ? $this->data['company'] : $this->data['firstname'] .' '. $this->data['lastname'],
        );

        $message = strtr(language::translate('email_body_customer_account_enabled', 'Your customer account has been enabled. Please sign in using your email %customer_email and the password you provided during registration.'), $aliases);

        $email = new email();
        $email->add_recipient($this->data['email'])
              ->set_subject(language::translate('email_subject_customer_account_enabled', 'Customer Account Enabled'))
              ->add_body($message)
              ->send();
      }
      
      } else {
        $customer_modules->update($this->data);
      }

      $this->previous = $this->data;

      cache::clear_cache('customers');
    }

    public function set_password($password) {

      if (empty($this->data['id'])) {
        $this->save();
      }

      database::query(
        "update ". DB_TABLE_CUSTOMERS ."
        set password_hash = '". database::input($this->data['password_hash'] = password_hash($password, PASSWORD_DEFAULT)) ."'
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $this->previous['password_hash'] = $this->data['password_hash'];
    }

    public function delete() {

      database::query(
        "update ". DB_TABLE_ORDERS ."
        set customer_id = 0
        where customer_id = ". (int)$this->data['id'] .";"
      );

      database::query(
        "delete from ". DB_TABLE_CUSTOMERS ."
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $customer_modules = new mod_customer();
      $customer_modules->delete($this->previous);

      $this->reset();

      cache::clear_cache('customers');
    }
  }
