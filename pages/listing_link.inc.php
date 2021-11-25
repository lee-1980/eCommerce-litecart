<?php
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;
  if (empty($_GET['sort'])) $_GET['sort'] = 'price';
  if (empty($_GET['listing_link_id'])) {
    header('Location: '. document::ilink('listing_links'));
    exit;
  }

  functions::draw_lightbox();

  $listing_link = reference::listing_link($_GET['listing_link_id']);

  if (empty($listing_link->id)) {
    http_response_code(410);
    echo language::translate('error_410_gone', 'The requested file is no longer available');
    return;
  }

  if (empty($listing_link->status)) {
    http_response_code(404);
    echo language::translate('error_404_not_found', 'The requested file could not be found');
    return;
  }

  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('listing_link', array('listing_link_id' => (int)$listing_link->id), false) .'" />';
  document::$snippets['title'][] = $listing_link->head_title ? $listing_link->head_title : $listing_link->name;
  document::$snippets['description'] = $listing_link->meta_description ? $listing_link->meta_description : strip_tags($listing_link->short_description);

  breadcrumbs::add(language::translate('title_listing_links', 'Listing Links'), document::ilink('listing_links'));
  breadcrumbs::add($listing_link->name);

  $_page = new ent_view();

  $listing_link_cache_token = cache::token('box_listing_link', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file');
  if (!$_page->snippets = cache::get($listing_link_cache_token, 'file', ($_GET['sort'] == 'popularity') ? 0 : 3600)) {

    $_page->snippets = array(
      'id' => $listing_link->id,
      'title' => $listing_link->h1_title ? $listing_link->h1_title : $listing_link->name,
      'name' => $listing_link->name,
      'description' => $listing_link->description,
      'link' => $listing_link->link,
      'image' => array(
        'original' => 'images/' . $listing_link->image,
        'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link->image, 200, 0, 'FIT_ONLY_BIGGER'),
        'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link->image, 200*2, 0, 'FIT_ONLY_BIGGER'),
      ),
      'products' => array(),
      'sort_alternatives' => array(
        'name' => language::translate('title_name', 'Name'),
        'price' => language::translate('title_price', 'Price'),
        'popularity' => language::translate('title_popularity', 'Popularity'),
        'date' => language::translate('title_date', 'Date'),
      ),
      'pagination' => null,
    );

    $products_query = functions::catalog_products_query(array(
      'listing_links' => array($listing_link->id),
      'sort' => $_GET['sort'],
      'campaigns_first' => true,
    ));

    if (database::num_rows($products_query) > 0) {
      if ($_GET['page'] > 1) database::seek($products_query, (settings::get('items_per_page', 20) * ($_GET['page']-1)));

      $page_items = 0;
      while ($listing_item = database::fetch($products_query)) {
        $_page->snippets['products'][] = $listing_item;

        if (++$page_items == settings::get('items_per_page', 20)) break;
      }
    }

    $_page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($products_query)/settings::get('items_per_page', 20)));

    cache::set($listing_link_cache_token, $_page->snippets);
  }

  echo $_page->stitch('pages/listing_link');
