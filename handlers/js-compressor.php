<?php
  require_once('../includes/config.inc.php');
  require_once('../includes/compatibility.inc.php');

  $requested_file = DOCUMENT_ROOT . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $js_file = DOCUMENT_ROOT . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $js_file_uncompressed = preg_replace('#(\.min\.js)$#', '.js', $js_file);
  $js_file_compressed = preg_replace('#(\.js)$#', '.min.js', $js_file_uncompressed);
  $is_minified_request = preg_match('#\.min\.js$#', $requested_file) ? true : false;

  ob_start('ob_gzhandler');

  header('Content-Type: application/javascript');
  header('Expires: ' . gmdate('r', strtotime('+7 days')));

  if (file_exists($js_file_uncompressed)) {
    $js = file_get_contents($js_file_uncompressed);

    require_once(FS_DIR_APP . 'ext/JShrink/Minifier.php');

    $minified_js = \JShrink\Minifier::minify($js, array('flaggedComments' => false));

    file_put_contents($js_file_compressed, $minified_js);

    if ($is_minified_request) {
      echo $minified_js;
    } else {
      echo $js;
    }

  } else if (file_exists($js_file)) {
    readfile($js_file);

  } else {

    http_response_code(404);
    die('File Not Found');
  }
