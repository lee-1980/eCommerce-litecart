<?php
  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    header('Content-type: text/html; charset='. language::$selected['charset']);
    document::$layout = 'ajax';
  }

  $box_checkout_discount_code = new ent_view();

  if (isset($_POST['discount_code'])) session::$data['discount_code'] = $_POST['discount_code'];

  if (!empty(session::$data['discount_code'])) {
    $discount_code_query = database::query(
      "select * from ". DB_TABLE_DISCOUNT_CODES ."
      where status
      and code = '". database::input(session::$data['discount_code']) ."'
      and (customers = '' or find_in_set(". (int)customer::$data['id'] .", customers))
      and (date_valid_from < '". date('Y-m-d H:i:s') ."')
      and (year(date_valid_to) <= '1971' or date_valid_to > '". date('Y-m-d H:i:s') ."')
      limit 1;"
    );
    $discount_code = database::fetch($discount_code_query);

    if (!empty($discount_code)) {
      $box_checkout_discount_code->snippets['css_background'] = ' style="background: #e1fae2 right/1em no-repeat;"';
    } else {
      $box_checkout_discount_code->snippets['css_background'] = ' style="background: #ffd4d4 right/1em no-repeat;"';
    }
  }

  if (empty($_POST['discount_code']) && !empty(session::$data['discount_code'])) $_POST['discount_code'] = session::$data['discount_code'];

  echo $box_checkout_discount_code->stitch('views/box_checkout_discount_code');
