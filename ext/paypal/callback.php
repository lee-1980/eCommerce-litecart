<?php

  define('REQUIRE_POST_TOKEN', false); // Allow unsigned external incoming POST data
  require_once('../../includes/app_header.inc.php');

// Log incoming call
  $request = "{$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']} {$_SERVER['SERVER_PROTOCOL']}\r\n";
  foreach (getallheaders() as $name => $value) {
    $request .= "$name: $value\r\n";
  }
  $request .= "\r\n" . file_get_contents('php://input');
  file_put_contents(FS_DIR_APP . 'logs/paypal_ipn_last.log', $request);

// Make sure we have an order ID
  if (empty($_GET['order_id'])) {
    trigger_error('Callback was missing order id', E_USER_WARNING);
    http_response_code(400);
    die('Bad Request');
  }

// Get Order From Database (In this example using UID)
// To be able to find the order in the database, the order must previously have been saved.
  $orders_query = database::query(
    "select id from ". DB_TABLE_ORDERS ."
    where id = '". database::input($_GET['order_id']) ."'
    limit 1;"
  );

  if (!$order = database::fetch($orders_query)) {
    trigger_error('Callback referred to an order id that is missing in the database', E_USER_WARNING);
    http_response_code(404);
    die('File Not Found');
  }

// Initiate $order as the order object
  $order = new ent_order($order['id']);

// Get the order's payment option
  list($module_id, $option_id) = explode(':', $order->data['payment_option']['id']);

  if ($module_id != 'pm_paypal_standard') {
    trigger_error('Callback referred to an order not made using Paypal', E_USER_WARNING);
    http_response_code(404);
    die('File Not Found');
  }

// Pass the call to the payment module's method callback()
  $payment = new mod_payment();
  $result = $payment->run('callback', $module_id, $order);

  // The rest is handled inside the payment module
  // Defined by the function: callback($order) {}
