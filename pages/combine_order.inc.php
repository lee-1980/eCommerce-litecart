<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php

header('X-Robots-Tag: noindex');
document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
document::$snippets['title'][] = language::translate('combined_order:head_title', 'Combine Order');

customer::require_login();

$_page = new ent_view();

$_page->snippets['orders'] = array();


if(!isset(session::$data['combine_orders']) || !is_array(session::$data['combine_orders'])){
    session::$data['combine_orders'] = array();
}

$orders_query = database::query(
    "select o.*, os.is_paid, os.is_combine, osi.name as order_status_name from ". DB_TABLE_ORDERS ." o
    left join " . DB_TABLE_ORDER_STATUSES . " os on ( os.id = o.order_status_id ) 
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
    where o.order_status_id
    and o.customer_id = ". (int)customer::$data['id'] ."
    and (not o.archived = 1 or o.archived IS NULL)
    and o.id IN ('". implode("', '", session::$data['combine_orders']) ."')
    order by o.date_created desc;"
);

if (database::num_rows($orders_query) > 0) {

    while ($order = database::fetch($orders_query)) {

        $orderx 	= new ctrl_order($order['id']);
        $items 		= (isset($orderx->data['items'])) ? $orderx->data['items'] : [];
        $order_totals = array_slice($orderx->data['order_total'], 1);

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

        $_page->snippets['orders'][] = array(
            'id' => $order['id'],
            'link' => (user::check_login()&&!empty($_GET['customerId']))? document::link(WS_DIR_ADMIN, array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id'], 'redirect_url' => $_SERVER['REQUEST_URI'])): document::ilink('order', array('order_id' => $order['id'], 'public_key' => $order['public_key'])),
            'order_status' => $order['order_status_name'],
            'is_paid' => isset($order['is_paid']) && $order['is_paid'] == 1? 'Paid': '',
            'date_created' => language::strftime(language::$selected['format_datetime'], strtotime($order['date_created'])),
            'payment_due' => currency::format($order['payment_due'], false, $order['currency_code'], $order['currency_value']),
            'items' => $items,
            'order_totals' => $order_totals,
            'payment_fee' => $payment_fee,
            'shipping_fee' => $shipping_fee,
            'customer_discount' => $customer_discount,
            'customer_payment' => $customer_payment,
            'discount_code' => $discount_code,
            'discount_nth_item' => $discount_nth_item,
            'fees' => $fees,
            'installment_fee' => $installment_fee,
            'paid_fee' => $paid_fee
        );

    }

}

echo $_page->stitch('pages/combine_order');
