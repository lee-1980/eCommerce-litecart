<?php
  if (empty($_GET['page'])) $_GET['page'] = 1;

  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('new_products') .'" />';
  document::$snippets['title'][] = language::translate('new_products:head_title', 'New Products');
  document::$snippets['description'] = language::translate('new_products:meta_description', 'New Products');;

  breadcrumbs::add(language::translate('title_new_products', 'New Products'));

  functions::draw_lightbox();

  $box_new_products_cache_token = cache::token('box_new_products', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file', 3600);
  if (cache::capture($box_new_products_cache_token)) {

    $_page = new ent_view();

    $_page->snippets['products'] = array();

    $items_per_page = settings::get('items_per_page');

    $products_query = functions::catalog_products_query(
      array(
        'sort' => 'date',
        'sql_where' => "p.date_created > '". date('Y-m-d H:i:s', strtotime('-'.settings::get('new_products_max_age'))) ."'",
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

    echo $_page->stitch('pages/new_products');

    cache::end_capture($box_new_products_cache_token);
  }
