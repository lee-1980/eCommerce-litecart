<?php

  class url_permalink {

    function routes() {

      $request = route::strip_url_logic($_SERVER['REQUEST_URI']);

      $permalinks_query = database::query(
      "select * from ". DB_TABLE_PERMALINKS ."
        where permalink = '". database::input($request) ."'
        or permalink = '". database::input(language::$selected['code'].'/'.$request) ."'
        limit 1;"
      );

      if ($permalink = database::fetch($permalinks_query)) {
        return array(
          array(
            'pattern' => '#^/?'. preg_quote($permalink['permalink'], '#') .'$#',
            'page' => $permalink['resource'],
            'params' => $permalink['resource'].'_id='.$permalink['resource_id'],
            'options' => array(
              'redirect' => true,
            ),
          ),
        );
      }
    }
  }
