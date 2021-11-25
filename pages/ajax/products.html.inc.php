<?php
  document::$layout = 'ajax';

  header('X-Robots-Tag: noindex');
  header('Content-type: text/html; charset='. language::$selected['charset']);

  if (empty($_GET['page'])) $_GET['page'] = 1;
  if (empty($_GET['sort'])) $_GET['sort'] = 'popularity';

  $filter = array();
  if (!empty($_GET['query'])) $filter['query'] = $_GET['query'];
  if (!empty($_GET['category_id'])) $filter['categories'] = array($_GET['category_id']);
  if (!empty($_GET['manufacturer_id'])) $filter['manufacturers'] = array($_GET['manufacturer_id']);
  if (!empty($_GET['attributes'])) $filter['attributes'] = $_GET['attributes'];
  if (!empty($_GET['price_ranges'])) $filter['price_ranges'] = $_GET['price_ranges'];
  if (!empty($_GET['campaign'])) $filter['campaign'] = $_GET['campaign'];
  if (!empty($_GET['page'])) $filter['page'] = (int)$_GET['page'];
  if (!empty($_GET['sort'])) $filter['sort'] = $_GET['sort'];

  $products_query = functions::catalog_products_query($filter);

  if (database::num_rows($products_query) && database::num_rows($products_query) > ($_GET['page']-1) * settings::get('items_per_page')) {

    if ($_GET['page'] > 1) database::seek($products_query, (settings::get('items_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($listing_product = database::fetch($products_query)) {

      echo functions::draw_listing_product($listing_product);

      if (++$page_items == settings::get('items_per_page', 20)) break;
    }
  }
