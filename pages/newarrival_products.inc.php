<?php
  if (empty($_GET['page'])) $_GET['page'] = 1;

  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('newarrival_products') .'" />';
  document::$snippets['title'][] = language::translate('newarrival_products:head_title', 'New Arrival Products');
  document::$snippets['description'] = language::translate('newarrival_products:meta_description', 'New Arrival Products');

  breadcrumbs::add(language::translate('title_newarrival_products', 'Newarrival Products'));

  functions::draw_lightbox();
 
  $box_newarrival_products_cache_id = cache::token('box_newarrival_products', array('basename', 'get', 'language', 'currency', 'account', 'prices'));
  if (cache::capture($box_newarrival_products_cache_id)) {

    $_page = new view();

    $_page->snippets['products'] = array();

    $items_per_page = settings::get('items_per_page');
    
    $products_query = functions::catalog_products_query(
      array(
        'sort' => 'p.date_updated',
        'sql_where' => "p.newarrival = 1  and p.date_valid_from > '".date('Y-m-d H:i:s')."' and p.date_valid_from <= '". date('Y-m-d H:i:s', strtotime('+'.settings::get('new_products_max_age'))) ."'",
      )
    );

    if (database::num_rows($products_query)) {
      if ($_GET['page'] > 1) database::seek($products_query, $items_per_page * ($_GET['page'] - 1));

      $page_items = 0;
      while ($listing_product = database::fetch($products_query)) {
        $listing_product['listing_type'] = 'column';
        $_page->snippets['products'][] = $listing_product;
        if (++$page_items == $items_per_page) break;
      }
    }

    $_page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($products_query)/$items_per_page));

    echo $_page->stitch('pages/newarrival_products');

    cache::end_capture($box_newarrival_products_cache_id);
  }
