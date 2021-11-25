<?php
  $box_listing_link_links_cache_token = cache::token('box_listing_link_links', array('language', isset($_GET['listing_link_id']) ? $_GET['listing_link_id'] : ''), 'file');
  if (cache::capture($box_listing_link_links_cache_token)) {

    $listing_links_query = database::query(
      "select a.id, a.name, a.date_created from ". DB_TABLE_LISTING_LINKS ." a
      left join ". DB_TABLE_LISTING_LINKS_INFO ." ai on (a.id = ai.listing_link_id and ai.language_code = '". language::$selected['code'] ."')
      where status
      order by a.name;"
    );

    if (database::num_rows($listing_links_query)) {

      $box_listing_link_links = new ent_view();

      $box_listing_link_links->snippets['listing_links'] = array();

      while ($listing_link = database::fetch($listing_links_query)) {
        $box_listing_link_links->snippets['listing_links'][] = array(
          'id' => $listing_link['id'],
          'name' => $listing_link['name'],
          'link' => document::ilink('listing_link', array('listing_link_id' => $listing_link['id'])),
          'date_created' => $listing_link['date_created'],
          'active' => (isset($_GET['listing_link_id']) && $_GET['listing_link_id'] == $listing_link['id']) ? true : false,
        );
      }

      echo $box_listing_link_links->stitch('views/box_listing_link_links');
    }

    cache::end_capture($box_listing_link_links_cache_token);
  }
