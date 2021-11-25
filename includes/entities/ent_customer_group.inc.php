<?php

  class ent_customer_group {
    public $data = array();

    public function __construct($group_id=null) {
      if ($group_id !== null) {
        $this->load((int)$group_id);
      } else {
        $this->reset();
      }
    }

    public function reset() {

      $this->data = array();

      $fields_query = database::query(
        "show fields from ". DB_TABLE_CUSTOMER_GROUPS .";"
      );
      while ($field = database::fetch($fields_query)) {
        $this->data[$field['Field']] = '';
      }
    }

    public function load($group_id) {

      $customer_group_query = database::query(
        "select * from ". DB_TABLE_CUSTOMER_GROUPS ."
        where id = '". database::input($group_id) ."'
        limit 1;"
      );
      $customer_group = database::fetch($customer_group_query);
      if (empty($customer_group)) trigger_error('Could not find customer group (ID: '. (int)$group_id .') in database.', E_USER_ERROR);

      $this->data = $customer_group;
    }

    public function save() {

      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_CUSTOMER_GROUPS ."
          (date_created)
          values ('". database::input(date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = database::insert_id();
      }

      database::query(
        "update ". DB_TABLE_CUSTOMER_GROUPS ."
        set
          name = '". database::input($this->data['name']) ."',
          description = '". database::input($this->data['description']) ."',
          date_updated = '". date('Y-m-d H:i:s') ."'
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );

      cache::clear_cache('customers');
      cache::clear_cache('customer_groups');
    }

    public function delete() {

      if (database::num_rows(database::query(
        "select id from ". DB_TABLE_CUSTOMERS ."
        where customer_group_id = ". (int)$this->data['id'] ."
        limit 1;"
      ))) die('Cannot delete customer group as there are customers linked to it');

      database::query(
        "delete from ". DB_TABLE_CUSTOMER_GROUPS ."
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );

      cache::clear_cache('customers');
      cache::clear_cache('customer_groups');

      $this->data['id'] = null;
    }
  }
