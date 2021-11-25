<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php
  header('X-Robots-Tag: noindex');
  document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
  document::$snippets['title'][] = language::translate('order_history:head_title', 'Order History');

  
      if(!(user::check_login()&&!empty($_GET['customerId']))) customer::require_login();
      

  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

  breadcrumbs::add(language::translate('title_account', 'Account'));
  breadcrumbs::add(language::translate('title_order_history', 'Order History'));

  $_page = new ent_view();

  $_page->snippets['orders'] = array();
  if(empty(session::$data['combine_orders'])) session::$data['combine_orders'] = array();

  
      $customerId = empty($_GET['customerId'])? (int)customer::$data['id'] : (int)$_GET['customerId'];
       $orders_query = database::query(
       
				
					"select o.*, os.is_paid, os.is_combine, osi.name as order_status_name from ". DB_TABLE_ORDERS ." o
				
			

				
					left join " . DB_TABLE_ORDER_STATUSES . " os on ( os.id = o.order_status_id )
				
			
       left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
       where o.order_status_id
       and o.customer_id = ". $customerId ."

				
					and (not o.archived = 1 or o.archived IS NULL)
					and o.id NOT IN ('". implode("', '", session::$data['combine_orders']) ."')
				
			
       order by o.date_created desc;"
      );
      $_page->snippets['admin_customerList'] = empty($_GET['customerId'])? null:"true";
      







  if (database::num_rows($orders_query) > 0) {
    if ($_GET['page'] > 1) database::seek($orders_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));
    $page_items = 0;

    while ($order = database::fetch($orders_query)) {

				
					$orderx 	= new ctrl_order($order['id']);
					$items 		= (isset($orderx->data['items'])) ? $orderx->data['items'] : [];
					$tracking_id  = (isset($orderx->data['shipping_tracking_id'])) ? $orderx->data['shipping_tracking_id'] : '';
					$order_totals = array_slice($orderx->data['order_total'], 1);
				
			
      
				
					// Hole alle Infos nach Bestellungs ID
					$orderx 	= new ctrl_order($order['id']);
					$items 		= (isset($orderx->data['items'])) ? $orderx->data['items'] : [];
					$tracking_id  = (isset($orderx->data['shipping_tracking_id'])) ? $orderx->data['shipping_tracking_id'] : '';
					$order_totals = array_slice($orderx->data['order_total'], 1);// Hole alles außer Zwischensumme (1.)

//					echo "<pre>" . print_r($orderx, true) . "</pre>";
//					exit;
					
					//Preset Vars
					$customer_discount = 0;
					$discount_code = 0;
					$discount_nth_item = 0;
					$shipping_fee = 0;
					$payment_fee = 0;
					$fees = 0;
					$installment_fee = 0;
					$paid_fee = 0;
					$customer_payment = 0;

					foreach ($order_totals as $value) {
					    if ($value["module_id"] == 'ot_customer_payment') {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$customer_payment 	-= $subval + $tax;	
							
						}  else if ($value["module_id"] == 'ot_discount_code') {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$discount_code 	-= $subval + $tax;								
							
						} else if (strpos($value["module_id"], 'shipping') !== false) {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$shipping_fee 	+= $subval + $tax;
							
						} else if (strpos($value["module_id"], 'payment') !== false) {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$payment_fee 	+= $subval + $tax;
							
						} else if (strpos($value["module_id"], 'discount_nth_item') !== false) {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$discount_nth_item 	-= $subval + $tax;								

						} else if (strpos($value["module_id"], 'installment') !== false) {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$installment_fee 	+= $subval + $tax;

						} else if (strpos($value["module_id"], 'customer_discount') !== false) {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$customer_discount 	-= $subval + $tax;	
							
						} else {
							$subval = (double) (isset($value["value"])) ? $value["value"] : 0; 
							$tax 	= (double) (isset($value["tax"])) ? $value["tax"] : 0; 
							$fees 			+= $subval + $tax;
						}						
					}
						
					$_page->snippets['orders'][]
				
			 = array(
        'id' => $order['id'],
        
      'link' => (user::check_login()&&!empty($_GET['customerId']))? document::link(WS_DIR_ADMIN, array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id'], 'redirect_url' => $_SERVER['REQUEST_URI'])): document::ilink('order', array('order_id' => $order['id'], 'public_key' => $order['public_key'])),
        'printable_link' => (user::check_login()&&!empty($_GET['customerId']))? document::link(WS_DIR_ADMIN, array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id'], 'redirect_url' => $_SERVER['REQUEST_URI'])): document::ilink('printable_order_copy', array('order_id' => $order['id'], 'public_key' => $order['public_key'])),
      

        'order_status' => $order['order_status_name'],
        'date_created' => language::strftime(language::$selected['format_datetime'], strtotime($order['date_created'])),
        'payment_due' => currency::format($order['payment_due'], false, $order['currency_code'], $order['currency_value']),

				
				    'is_combine' => $order['is_combine'],
					'items' => $items,
					'order_totals' => $order_totals,
					'tracking_id' => $tracking_id,
					'description' => '',
					'items' => $items,
					'payment_fee' => $payment_fee,
					'shipping_fee' => $shipping_fee,
					'customer_discount' => $customer_discount,
					'customer_payment' => $customer_payment,
					'discount_code' => $discount_code,
                    'discount_nth_item' => $discount_nth_item,					
					'fees' => $fees,
					'fees1' => $fees1,
					'fees2' => $fees2,
					'fees3' => $fees3,
					'fees4' => $fees4,
					'tracking_id' => $tracking_id,
					'installment_fee' => $installment_fee,
					'paid_fee' => $paid_fee,
				
			
      );
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }

  $_page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($orders_query)/settings::get('data_table_rows_per_page')));

  echo $_page->stitch('pages/order_history');
