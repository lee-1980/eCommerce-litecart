<?php
  if (settings::get('box_restock_products_num_items') == 0) return;

  functions::draw_lightbox();

  $box_restock_products_cache_id = cache::cache_id('box_restock_products', array('language', 'currency', 'prices'));
  if (cache::capture($box_restock_products_cache_id, 'file')) {

    $box_restock_products = new view();

    $products_query = functions::catalog_products_query(array('p.restock' => true, 'sort' => 'date', 'limit' => settings::get('box_restock_products_num_items')));

    if (database::num_rows($products_query)) {

      $box_restock_products->snippets['products'] = array();
      while ($listing_product = database::fetch($products_query)) {
        $box_restock_products->snippets['products'][] = $listing_product;
      }

      echo $box_restock_products->stitch('views/box_restock_products');
    }

    cache::end_capture($box_restock_products_cache_id);
  }
