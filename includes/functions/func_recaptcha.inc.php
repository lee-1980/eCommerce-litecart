<?php

  function recaptcha_validate($response) {

    $link = document::link('https://www.google.com/recaptcha/api/siteverify', array('secret' => '6LdsxFMUAAAAAIzik4ibzoMYQxfiyPvWQGIu4XNk', 'response' => $response, 'remoteip' => $_SERVER['REMOTE_ADDR']));

    $result = file_get_contents($link);

    if ($json = json_decode($result, true)) {
      if (!empty($json['success'])) return true;
    }

    return false;
  }

  function recaptcha_draw() {

    document::$snippets['foot_tags']['recaptcha'] = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

    return '<div class="g-recaptcha" data-sitekey="6LdsxFMUAAAAAObRkgzuq5nF7KA30GhKdP-moIsO"></div>';
  }
