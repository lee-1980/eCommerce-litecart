<?php

  class ent_discount_code {
    public $data = array();

    public function __construct($discount_code_id=null) {
      if ($discount_code_id !== null) $this->load($discount_code_id);
    }

    public function load($discount_code_id) {

      $discount_code_query = database::query(
        "select * from ". DB_TABLE_DISCOUNT_CODES ."
        where id = '". (int)$discount_code_id ."'
        limit 1;"
      );
      $this->data = database::fetch($discount_code_query);
      if (empty($this->data)) trigger_error('Could not find discount code ('. $discount_code_id .') in database.', E_USER_ERROR);

      $this->data['customers'] = $this->data['customers'] ? explode(',', $this->data['customers']) : array();
      $this->data['categories'] = $this->data['categories'] ? explode(',', $this->data['categories']) : array();
      $this->data['manufacturers'] = $this->data['manufacturers'] ? explode(',', $this->data['manufacturers']) : array();
      $this->data['products'] = $this->data['products'] ? explode(',', $this->data['products']) : array();
    }

    public function save() {

      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_DISCOUNT_CODES ."
          (date_created)
          values ('". database::input(date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = database::insert_id();
      }

      database::query(
        "update ". DB_TABLE_DISCOUNT_CODES ."
        set status = '". ((!empty($this->data['status'])) ? 1 : 0) ."',
          code = '". database::input($this->data['code']) ."',
          description = '". database::input($this->data['description']) ."',
          discount = '". database::input($this->data['discount']) ."',
          min_subtotal_amount = '". (float)$this->data['min_subtotal_amount'] ."',
          max_use_customer = '". (float)$this->data['max_use_customer'] ."',
          max_use_total = '". (float)$this->data['max_use_total'] ."',
          customers = '". (!empty($this->data['customers']) ? implode(',', database::input($this->data['customers'])) : '') ."',
          categories = '". (!empty($this->data['categories']) ? implode(',', database::input($this->data['categories'])) : '') ."',
          manufacturers = '". (!empty($this->data['manufacturers']) ? implode(',', database::input($this->data['manufacturers'])) : '') ."',
          products = '". (!empty($this->data['products']) ? implode(',', database::input($this->data['products'])) : '') ."',
          limited = ". (!empty($this->data['limited']) ? 1 : 0) .",
          date_valid_from = '". database::input($this->data['date_valid_from']) ."',
          date_valid_to = '". database::input($this->data['date_valid_to']) ."',
          date_updated = '". date('Y-m-d H:i:s') ."'
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );

      cache::clear_cache('discount_code');
    }

    public function delete() {

      database::query(
        "delete from ". DB_TABLE_DISCOUNT_CODES ."
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );

      $this->data['id'] = null;

      cache::clear_cache('discount_code');
    }
  }
