<?php

  $box_listing_link_logotypes_cache_token = cache::token('box_listing_link_logotypes', array(), 'file');
  if (cache::capture($box_listing_link_logotypes_cache_token)) {

    $listing_links_query = database::query(
      "select id, image, name from ". DB_TABLE_LISTING_LINKS ."
      where status
      and featured
      and image != ''
      order by rand();"
    );

    if (database::num_rows($listing_links_query)) {

      $box_listing_link_logotypes = new ent_view();

      $box_listing_link_logotypes->snippets['logotypes'] = array();

      while ($listing_link = database::fetch($listing_links_query)) {
        $box_listing_link_logotypes->snippets['logotypes'][] = array(
          'title' => $listing_link['name'],
          'link' => document::ilink('listing_link', array('listing_link_id' => $listing_link['id'])),
          'image' => array(
            'original' => 'images/' . $listing_link['image'],
            'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link['image'], 0, 30, 'FIT'),
            'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link['image'], 0, 60, 'FIT'),
          ),
        );
      }

      echo $box_listing_link_logotypes->stitch('views/box_listing_link_logotypes');
    }

    cache::end_capture($box_listing_link_logotypes_cache_token);
  }
