<?php

  return $app_config = array(
    'name' => language::translate('title_discount_codes', 'Discount Codes'),
    'default' => 'discount_codes',
    'menu' => array(
      array(
        'title' => language::translate('title_discount_codes', 'Discount Codes'),
        'doc' => 'discount_codes',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_used_discount_codes', 'Used Discount Codes'),
        'doc' => 'used_discount_codes',
        'params' => array(),
      ),
    ),
    'docs' => array(
      'discount_codes' => 'discount_codes.inc.php',
      'edit_discount_code' => 'edit_discount_code.inc.php',
      'used_discount_codes' => 'used_discount_codes.inc.php',
    ),
  );
