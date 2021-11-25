<?php

class ent_guest_price {
    public $data = array();

    public function __construct($price_id=null) {
        if ($price_id !== null) {
            $this->load((int)$price_id);
        } else {
            $this->reset();
        }
    }

    public function reset() {

        $this->data = array();

        $fields_query = database::query(
            "show fields from ". DB_TABLE_PRODUCTS_GUEST_PRICES .";"
        );
        while ($field = database::fetch($fields_query)) {
            $this->data[$field['Field']] = '';
        }
    }

    public function load($price_id) {

        $guest_prices_query = database::query(
            "select * from ". DB_TABLE_PRODUCTS_GUEST_PRICES ."
        where id = '". database::input($price_id) ."'
        limit 1;"
        );
        $guest_price = database::fetch($guest_prices_query);
        if (empty($guest_price)) trigger_error('Could not find Guest Prices (ID: '. (int)$price_id .') in database.', E_USER_ERROR);

        $this->data = $guest_price;

    }

    public function save() {

        if ((empty($this->data['id']))) {
            database::query(
                "insert into ". DB_TABLE_PRODUCTS_GUEST_PRICES ."
          (date_created)
          values ('". database::input(date('Y-m-d H:i:s')) ."');"
            );
            $this->data['id'] = database::insert_id();
        }

        database::query(
            "update ". DB_TABLE_PRODUCTS_GUEST_PRICES ."
        set
          name = '". database::input($this->data['name']) ."',
          description = '". database::input($this->data['description']) ."',
          date_updated = '". date('Y-m-d H:i:s') ."'
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
        );

        cache::clear_cache('guest_prices');
    }
    public function delete() {

        database::query(
            "delete from ". DB_TABLE_PRODUCTS_GUEST_PRICES ."
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
        );

        cache::clear_cache('guest_prices');

        $this->data['id'] = null;
    }
}

      
