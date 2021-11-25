<?php

  class visitor {

    public static function init() {

      //if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) return;

      include FS_DIR_APP . 'ext/CrawlerDetect/CrawlerDetect.php';
      $CrawlerDetect = new CrawlerDetect();
      if ($CrawlerDetect->isCrawler()) return;

      if (!defined('DB_TABLE_VISITORS')) define('DB_TABLE_VISITORS', '`'.DB_TABLE_PREFIX . 'visitors`');

      if (empty($_COOKIE['visitor']['uid'])) {
        $_COOKIE['visitor']['uid'] = uniqid();
      }

      if (!empty($_COOKIE['cookies_accepted'])) {
        setcookie('visitor[uid]', $_COOKIE['visitor']['uid'], 0, WS_DIR_APP);
      }

      database::query(
        "delete from ". DB_TABLE_VISITORS ."
        where date_updated < '". date('Y-m-d H:i:s', strtotime('-30 days')) ."';"
      );

      $visitor_query = database::query(
        "select id from ". DB_TABLE_VISITORS ."
        where uid = '". database::input($_COOKIE['visitor']['uid']) ."'
        and date_updated > '". date('Y-m-d 00:00:00') ."'
        order by date_updated desc
        limit 1;"
      );

      if (!$visitor = database::fetch($visitor_query)) {
        $visitor_query = database::query(
          "select id from ". DB_TABLE_VISITORS ."
          where ip = '". database::input($_SERVER['REMOTE_ADDR']) ."'
          and user_agent = '". database::input($_SERVER['HTTP_USER_AGENT'])."'
          and date_updated > '". date('Y-m-d 00:00:00') ."'
          order by date_updated desc
          limit 1;"
        );
      }

      if (!$visitor = database::fetch($visitor_query)) {
        database::query(
          "insert into ". DB_TABLE_VISITORS ."
          (uid, ip, host, user_agent, referrer, date_updated, date_created)
          values ('". database::input($_COOKIE['visitor']['uid']) ."', '". database::input($_SERVER['REMOTE_ADDR'])."', '". database::input(gethostbyaddr($_SERVER['REMOTE_ADDR'])) ."', '". database::input($_SERVER['HTTP_USER_AGENT'])."', '". @database::input($_SERVER['HTTP_REFERER'])."', '". date('Y-m-d H:i:s') ."', '". date('Y-m-d H:i:s') ."');"
        );

        $visitor['id'] = database::insert_id();
      }

      database::query(
        "update ". DB_TABLE_VISITORS ."
        set pageviews = pageviews + 1,
        cart_uid = '". database::input(cart::$data['uid']) ."',
        language = '". database::input(language::$selected['code']) ."',
        country = '". database::input(customer::$data['country_code']) ."',
        last_page = '". database::input((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ."',
        ip = '". database::input($_SERVER['REMOTE_ADDR']) ."',
        host = '". database::input(gethostbyaddr($_SERVER['REMOTE_ADDR'])) ."',
        user_agent = '". database::input($_SERVER['HTTP_USER_AGENT']) ."',
        date_updated = '". date('Y-m-d H:i:s') ."'
        where id = ". (int)$visitor['id'] ."
        limit 1;"
      );
    }
  }
