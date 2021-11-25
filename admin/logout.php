<?php
  require_once('../includes/app_header.inc.php');

  header('X-Robots-Tag: noindex');
  document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';

  user::reset();

  header('Set-Cookie: remember_me=; path='. WS_DIR_APP .'; max-age=-1; HttpOnly; SameSite=Strict', false);

  header('Location: ' . document::link(WS_DIR_ADMIN . 'login.php'));
  exit;
