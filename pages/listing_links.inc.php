<?php
  document::$snippets['title'][] = language::translate('listing_links:head_title', 'Listing Links');
  document::$snippets['description'] = language::translate('listing_links:meta_description', '');

  breadcrumbs::add(language::translate('title_listing_links', 'Listing Links''));

  $listing_links_cache_token = cache::token('listing_links', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file');
  if (cache::capture($listing_links_cache_token)) {

    $_page = new ent_view();

    $listing_links_query = database::query(
      "select m.id, m.name, m.image, mi.short_description, mi.link
      from ". DB_TABLE_LISTING_LINKS ." m
      left join ". DB_TABLE_LISTING_LINKS_INFO ." mi on (mi.listing_link_id = m.id and mi.language_code = '". language::$selected['code'] ."')
      where status
      order by name;"
    );

    $_page->snippets['listing_links'] = array();

    while ($listing_link = database::fetch($listing_links_query)) {
      $_page->snippets['listing_links'][] = array(
        'id' => $listing_link['id'],
        'name' => $listing_link['name'],
        'image' => array(
          'original' => 'images/' . $listing_link['image'],
          'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link['image'], 320, 100, 'FIT_ONLY_BIGGER_USE_WHITESPACING'),
          'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link['image'], 640, 200, 'FIT_ONLY_BIGGER_USE_WHITESPACING'),
        ),
        'link' => document::ilink('listing_link', array('listing_link_id' => $listing_link['id'])),
      );
    }

    echo $_page->stitch('pages/listing_links');

    cache::end_capture($listing_links_cache_token);
  }
