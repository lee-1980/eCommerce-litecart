<?php
  $settings = '';
  $settings_query = database::query('SELECT * FROM `'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'eyecandy_modules_settings` WHERE `module_key` = "PRODUCTSEARCH";');
  /*
    Functionality to add:
    (https://fusejs.io/) Weighted search - the ability to give higher weight to e.g popular products
  */

  if(database::num_rows($settings_query) > 0) 
  {
    $settings = database::fetch($settings_query)['settings_json'];
  }
  else
  {
    $settings = json_encode(array(
      'fetch' => array(
        'fetch-product-category' => true,
        'search-hit-name' => true,
        'search-hit-short-descr' => true,
        'search-hit-descr' => false,
        'search-hit-keywords' => false,
        'search-hit-manufacturers' => true,
        'search-hit-suppliers' => true,
      ),
      'js' => array(
        'result_amount' => 6,
        'padding' => 5,
        'result_row1' => 'name',
        'result_row2' => false,
        'shouldSort' => true,
        'threshold' => 0.6,
        'location' => 0,
        'distance' => 100,
        'maxPatternLength' => 32,
        'width' => 0,
        'keys' => array( 
          0 => 'name',
          1 => 'keywords',
          2 => 'sdescr',
          3 => 'descr',
          4 => 'manufacturer',
          5 => 'supplier',
        ),
      ),
    ));
  }

  $settings = json_decode($settings, true);

  if(isset($settings['fetch']))
  {
    $name = '';
    $sdescr = '';
    $descr = '';
    $keywrd = '';
    $category = '';
    $manufacturer = '';

    $join = '';

    if(isset($settings['fetch']['search-hit-name']) && $settings['fetch']['search-hit-name'])
    {
      $name = ' ,pi.name as name';
    }

    if(isset($settings['fetch']['search-hit-short-descr']) && $settings['fetch']['search-hit-short-descr'])
    {
      $sdescr = ' ,pi.short_description as sdescr';
    }

    if(isset($settings['fetch']['search-hit-descr']) && $settings['fetch']['search-hit-descr'])
    {
      $descr = ' ,pi.description as descr';
    }

    if(isset($settings['fetch']['search-hit-keywords']) && $settings['fetch']['search-hit-keywords'])
    {
      $keywrd = ' ,p.keywords as keywords';
    }

    if(isset($settings['fetch']['fetch-product-category']) && $settings['fetch']['fetch-product-category'])
    {
      $category = ' , ci.name as category, ci.short_description as category_desc';
      $join .= ' left join '.DB_TABLE_CATEGORIES_INFO.' ci on (ci.category_id = p.default_category_id and ci.language_code = "'. language::$selected['code'] .'")';
    }

    if(isset($settings['fetch']['search-hit-manufacturers']) && $settings['fetch']['search-hit-manufacturers'])
    {
      $manufacturer = ' , m.name as manufacturer';
      $join .= ' left join '.DB_TABLE_MANUFACTURERS.' m on (m.id = p.manufacturer_id)';
    }

    if(isset($settings['fetch']['search-hit-suppliers']) && $settings['fetch']['search-hit-suppliers'])
    {
      $supplier = ' , s.name as supplier';
      $join .= ' left join '.DB_TABLE_SUPPLIERS.' s on (s.id = p.supplier_id)';
    }

    $query = database::query(
      "select p.id as id".$name.$sdescr.$descr.$keywrd.$category.$manufacturer.$supplier."
      from ". DB_TABLE_PRODUCTS ." p ". $join ."
      left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
      where p.status = 1;"
    );
  }
  else
  {
    $query = database::query(
      "select p.id as id, pi.name as name, p.keywords as keywords, pi.short_description as descr 
      from ". DB_TABLE_PRODUCTS ." p
      left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."');"
    );
  }

  $result = [];

  if(database::num_rows($query) > 0) 
  {
    while($post = database::fetch($query)) 
    {
       $result[] = $post;
    }
  }

  document::$snippets['foot_tags']['products_list'] = '<div id="eycandy_products_result" style="display:none;">'.json_encode($result).'</div>';
  document::$snippets['foot_tags']['products_search_settings'] = '<div id="eycandy_products_search_settings" style="display:none;">'.json_encode($settings['js']).'</div>';
?>