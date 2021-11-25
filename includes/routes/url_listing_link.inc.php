<?php

  class url_listing_link {

    function routes() {
      return array(
        array(
          'pattern' => '#^.*-m-([0-9]+)/?$#',
          'page' => 'listing_link',
          'params' => 'listing_link_id=$1',
          'options' => array(
            'redirect' => true,
          ),
        ),
      );
    }

    function rewrite(ent_link $link, $language_code) {

      if (empty($link->query['listing_link_id'])) return;

      $listing_link = reference::listing_link($link->query['listing_link_id'], $language_code);
      if (empty($listing_link->id)) return $link;

      $link->path = functions::general_path_friendly($listing_link->name, $language_code) .'-m-'. $listing_link->id .'/';
      $link->unset_query('listing_link_id');

      return $link;
    }
  }
