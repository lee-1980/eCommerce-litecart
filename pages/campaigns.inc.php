<?php
  if (empty($_GET['page'])) $_GET['page'] = 1;

  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('campaigns') .'" />';
  document::$snippets['title'][] = language::translate('campaigns:head_title', 'Campaigns');
  document::$snippets['description'] = language::translate('campaigns:meta_description', '');

  breadcrumbs::add(language::translate('title_campaigns', 'Campaigns'));

  functions::draw_lightbox();

  $box_campaigns_cache_token = cache::token('box_campaigns', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file', 3600);
  if (cache::capture($box_campaigns_cache_token)) {

    $page = new ent_view();

    $page->snippets['products'] = array();

    $items_per_page = settings::get('items_per_page');

    $products_query = functions::catalog_products_query(
      array(
        'sort' => 'price',
        'sql_where' => "p.master_insane_deal_price = 1  and p.insaneprice = 1 or p.disable_master_insane_deal_price = 1   ",

      )
    );

    if (database::num_rows($products_query)) {
      if ($_GET['page'] > 1) database::seek($products_query, $items_per_page * ($_GET['page'] - 1));

      $page_items = 0;
      while ($listing_product = database::fetch($products_query)) {
        $listing_product['listing_type'] = 'column';
        $page->snippets['products'][] = $listing_product;
        if (++$page_items == $items_per_page) break;
      }
    }

    $page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($products_query)/$items_per_page));

    echo $page->stitch('pages/campaigns');

    cache::end_capture($box_campaigns_cache_token);
  }
