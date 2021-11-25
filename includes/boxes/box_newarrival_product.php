<?php
  if (settings::get('box_newarrival_products_num_items') == 0) return;

  functions::draw_lightbox();

  $box_newarrival_products_cache_id = cache::cache_id('box_newarrival_products', array('language', 'currency', 'prices'));
  if (cache::capture($box_newarrival_products_cache_id, 'file')) {

    $box_newarrival_products = new view();

    $products_query = functions::catalog_products_query(array('p.newarrival' => true, 'sort' => 'date', 'limit' => settings::get('box_newarrival_products_num_items')));

    if (database::num_rows($products_query)) {

      $box_newarrival_products->snippets['products'] = array();
      while ($listing_product = database::fetch($products_query)) {
        $box_newarrival_products->snippets['products'][] = $listing_product;
      }

      echo $box_newarrival_products->stitch('views/box_newarrival_products');
    }

    cache::end_capture($box_newarrival_products_cache_id);
  }

