<?php

  class ref_listing_link {

    private $_id;
    private $_cache_token;
    private $_language_codes;
    private $_data = array();

    function __construct($listing_link_id, $language_code=null) {

      if (empty($language_code)) $language_code = language::$selected['code'];

      $this->_id = (int)$listing_link_id;
      $this->_cache_token = cache::token('listing_link_'.(int)$listing_link_id, array($language_code), 'file');
      $this->_language_codes = array_unique(array(
        $language_code,
        settings::get('default_language_code'),
        settings::get('store_language_code'),
      ));

      if ($cache = cache::get($this->_cache_token)) {
        $this->_data = $cache;
      }
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
      trigger_error('Setting data is prohibited ('.$name.')', E_USER_WARNING);
    }

    private function _load($field) {

      switch($field) {

        case 'description':
        case 'short_description':
        case 'head_title':
        case 'meta_description':
        case 'h1_title':
        case 'link':

          $this->_data['info'] = array();

          $query = database::query(
            "select * from ". DB_TABLE_LISTING_LINKS_INFO ."
            where listing_link_id = ". (int)$this->_id ."
            and language_code in ('". implode("', '", database::input($this->_language_codes)) ."')
            order by field(language_code, '". implode("', '", database::input($this->_language_codes)) ."');"
          );

          while ($row = database::fetch($query)) {
            foreach ($row as $key => $value) {
              if (in_array($key, array('id', 'listing_link_id', 'language_code'))) continue;
              if (empty($this->_data[$key])) $this->_data[$key] = $value;
            }
          }

          break;

        default:

          $query = database::query(
            "select * from ". DB_TABLE_LISTING_LINKS ."
            where id = ". (int)$this->_id ."
            limit 1;"
          );

          if (!$row = database::fetch($query)) return;

          foreach ($row as $key => $value) {
            switch($key) {
              case 'keywords':
                $row[$key] = explode(',', $row[$key]);
                break;

              default:
                $this->_data[$key] = $value;
                break;
            }
          }

          break;
      }

      cache::set($this->_cache_token, $this->_data);
    }
  }
