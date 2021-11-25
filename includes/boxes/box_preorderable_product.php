<?php
  if (settings::get('box_pre_order_products_num_items') == 0) return;

  functions::draw_lightbox();

  $box_pre_order_products_cache_id = cache::cache_id('box_pre_order_products', array('language', 'currency', 'prices'));
  if (cache::capture($box_pre_order_products_cache_id, 'file')) {

    $box_pre_order_products = new view();

    $products_query = functions::catalog_products_query(array('p.pre_order' => true, 'sort' => 'date', 'limit' => settings::get('box_pre_order_products_num_items')));

    if (database::num_rows($products_query)) {

      $box_pre_order_products->snippets['products'] = array();
      while ($listing_product = database::fetch($products_query)) {
        $box_pre_order_products->snippets['products'][] = $listing_product;
      }

      echo $box_pre_order_products->stitch('views/box_pre_order_products');
    }

    cache::end_capture($box_pre_order_products_cache_id);
  }

