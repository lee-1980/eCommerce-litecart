<?php

  class ent_listing_link {
    public $data;
    public $previous;

    public function __construct($listing_link_id='') {

      if (!empty($listing_link_id)) {
        $this->load($listing_link_id);
      } else {
        $this->reset();
      }
    }

    public function reset() {

      $this->data = array();

      $listing_link_query = database::query(
        "show fields from ". DB_TABLE_LISTING_LINKS .";"
      );

      while ($field = database::fetch($listing_link_query)) {
        $this->data[$field['Field']] = null;
      }

      $listing_link_info_query = database::query(
        "show fields from ". DB_TABLE_LISTING_LINKS_INFO .";"
      );
      while ($field = database::fetch($listing_link_info_query)) {
        if (in_array($field['Field'], array('id', 'listing_link_id', 'language_code'))) continue;

        $this->data[$field['Field']] = array();
        foreach (array_keys(language::$languages) as $language_code) {
          $this->data[$field['Field']][$language_code] = null;
        }
      }

      $this->previous = $this->data;
    }

    public function load($listing_link_id) {

      if (!preg_match('#^[0-9]+$#', $listing_link_id)) throw new Exception('Invalid listing link (ID: '. $listing_link_id .')');

      $this->reset();

      $listing_links_query = database::query(
        "select * from ". DB_TABLE_LISTING_LINKS ."
        where id=". (int)$listing_link_id ."
        limit 1;"
      );

      if ($listing_link = database::fetch($listing_links_query)) {
        $this->data = array_replace($this->data, array_intersect_key($listing_link, $this->data));
      } else {
        throw new Exception('Could not find listing link (ID: '. (int)$listing_link_id .') in database.');
      }

      $listing_links_info_query = database::query(
        "select * from ". DB_TABLE_LISTING_LINKS_INFO ."
        where listing_link_id = ". (int)$listing_link_id .";"
      );

      while ($listing_link_info = database::fetch($listing_links_info_query)) {
        foreach ($listing_link_info as $key => $value) {
          if (in_array($key, array('id', 'listing_link_id', 'language_code'))) continue;
          $this->data[$key][$listing_link_info['language_code']] = $value;
        }
      }

      $this->previous = $this->data;
    }

    public function save() {

      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_LISTING_LINKS ."
          (date_created)
          values ('". ($this->data['date_created'] = date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = database::insert_id();
      }

      $this->data['keywords'] = explode(',', $this->data['keywords']);
      $this->data['keywords'] = array_map('trim', $this->data['keywords']);
      $this->data['keywords'] = array_unique($this->data['keywords']);
      $this->data['keywords'] = implode(',', $this->data['keywords']);

      database::query(
        "update ". DB_TABLE_LISTING_LINKS ." set
        status = ". (int)$this->data['status'] .",
        featured = ". (int)$this->data['featured'] .",
        code = '". database::input($this->data['code']) ."',
        name = '". database::input($this->data['name']) ."',
        image = '". database::input($this->data['image']) ."',
        keywords = '". database::input($this->data['keywords']) ."'
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      foreach (array_keys(language::$languages) as $language_code) {

        $listing_links_info_query = database::query(
          "select * from ". DB_TABLE_LISTING_LINKS_INFO ."
          where listing_link_id = ". (int)$this->data['id'] ."
          and language_code = '". database::input($language_code) ."'
          limit 1;"
        );

        if (!$listing_link_info = database::fetch($listing_links_info_query)) {
          database::query(
            "insert into ". DB_TABLE_LISTING_LINKS_INFO ."
            (listing_link_id, language_code)
            values (". (int)$this->data['id'] .", '". database::input($language_code) ."');"
          );
        }

        database::query(
          "update ". DB_TABLE_LISTING_LINKS_INFO ." set
          short_description = '". database::input($this->data['short_description'][$language_code]) ."',
          description = '". database::input($this->data['description'][$language_code], true) ."',
          head_title = '". database::input($this->data['head_title'][$language_code]) ."',
          h1_title = '". database::input($this->data['h1_title'][$language_code]) ."',
          meta_description = '". database::input($this->data['meta_description'][$language_code]) ."',
          link = '". database::input($this->data['link'][$language_code]) ."'
          where listing_link_id = ". (int)$this->data['id'] ."
          and language_code = '". database::input($language_code) ."'
          limit 1;"
        );
      }

      $this->previous = $this->data;

      cache::clear_cache('listing_links');
    }

    public function save_image($file) {

      if (empty($file)) return;

      if (empty($this->data['id'])) {
        $this->save();
      }

      if (!is_dir(FS_DIR_APP . 'images/listing_links/')) mkdir(FS_DIR_APP . 'images/listing_links/', 0777);

      $image = new ent_image($file);

    // 456-12345_Fancy-title.jpg
      $filename = 'listing_links/' . $this->data['id'] .'-'. functions::general_path_friendly($this->data['name'], settings::get('store_language_code')) .'.'. $image->type();

      if (is_file(FS_DIR_APP . 'images/' . $this->data['image'])) unlink(FS_DIR_APP . 'images/' . $this->data['image']);

      functions::image_delete_cache(FS_DIR_APP . 'images/' . $filename);

      if (settings::get('image_downsample_size')) {
        list($width, $height) = explode(',', settings::get('image_downsample_size'));
        $image->resample($width, $height, 'FIT_ONLY_BIGGER');
      }

      $image->write(FS_DIR_APP . 'images/' . $filename, '', 90);

      database::query(
        "update ". DB_TABLE_LISTING_LINKS ."
        set image = '". database::input($filename) ."'
        where id = ". (int)$this->data['id'] .";"
      );

      $this->previous['image'] = $this->data['image'] = $filename;
    }

    public function delete_image() {

      if (empty($this->data['id'])) return;

      if (is_file(FS_DIR_APP . 'images/' . $this->data['image'])) unlink(FS_DIR_APP . 'images/' . $this->data['image']);

      functions::image_delete_cache(FS_DIR_APP . 'images/' . $this->data['image']);

      database::query(
        "update ". DB_TABLE_LISTING_LINKS ."
        set image = ''
        where id = ". (int)$this->data['id'] .";"
      );

      $this->previous['image'] = $this->data['image'] = '';
    }

    public function delete() {

      if (empty($this->data['id'])) return;

      $products_query = database::query(
        "select id from ". DB_TABLE_PRODUCTS ."
        where listing_link_id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      if (database::num_rows($products_query) > 0) {
        notices::add('errors', language::translate('error_delete_listing_link_not_empty_products', 'The listing link could not be deleted because there are products linked to it.'));
        header('Location: '. $_SERVER['REQUEST_URI']);
        exit;
      }

      if (!empty($this->data['image']) && is_file(FS_DIR_APP . 'images/listing_links/' . $this->data['image'])) {
        unlink(FS_DIR_APP . 'images/listing_links/' . $this->data['image']);
      }

      database::query(
        "delete from ". DB_TABLE_LISTING_LINKS ."
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      database::query(
        "delete from ". DB_TABLE_LISTING_LINKS_INFO ."
        where listing_link_id = ". (int)$this->data['id'] .";"
      );

      $this->reset();

      cache::clear_cache('listing_links');
    }
  }
