<?php
header('X-Robots-Tag: noindex');
document::$layout = 'checkout';

$shipping = new mod_shipping();


if (empty(session::$data['order']) || empty(session::$data['combined_new_items']['sco_items'])) {
    notices::add('errors', 'Missing order object');
    header('Location: '. document::ilink('combined_checkout'));
    exit;
}

$order = &session::$data['order'];
$combine_order_totals = array_slice($order->data['order_total'], 1);
$priority = count($order->data['order_total']) + 1;
$current_date = date('Y-m-d (h:i:sa)');

if ($error_message = $order->validate($shipping)) {
    notices::add('errors', $error_message);
    header('Location: '. document::ilink('combined_checkout'));
    exit;
}

$new_combine_customer_payment = array(
    'order_id' => $order->data['id'],
    'module_id' => 'ot_customer_payment',
    'title' => $current_date,
    'value' => 0,
    'tax' => 0,
    'calculate' => 1,
    'priority' => (int) $priority,
);

// If Confirm Order button was pressed
if (isset($_POST['confirm_order'])) {

    foreach(session::$data['combined_new_items']['sco_items'] as $order_id => $products){

        $orderx = new ctrl_order($order_id);
        $items 		= (isset($orderx->data['items'])) ? $orderx->data['items'] : [];
        $order_totals = array_slice($orderx->data['order_total'], 1);
        $product_ids = array_column($items, 'product_id', 'id');

        $customer_payment = 0;
        $merged_customer_payment_id = '';


        foreach ($order_totals as $key => $value) {
            if ($value["module_id"] == 'ot_customer_payment' && $value['calculate'] == 1) {
                if(empty($merged_customer_payment_id)) {
                    $merged_customer_payment_id = $key;
                    $order_totals[$key]['value'] = (double)(isset($value["value"])) ? $value["value"] : 0;
                    $order_totals[$key]['tax'] =(double)(isset($value["tax"])) ? $value["tax"] : 0;
                    continue;
                }
                $order_totals[$merged_customer_payment_id]['value'] += (double)(isset($value["value"])) ? $value["value"] : 0;
                $order_totals[$merged_customer_payment_id]['tax'] += (double)(isset($value["tax"])) ? $value["tax"] : 0;

                unset($order_totals[$key]);
            }
        }

        $customer_payment = abs($order_totals[$merged_customer_payment_id]['value'] + $order_totals[$merged_customer_payment_id]['tax']);


        $combined_items_price = 0;
        foreach ($products as $combined_item_id) {
            $order_item_id = array_search($combined_item_id, $product_ids);
            $item_price = $items[$order_item_id]['price'];
            $combined_items_price += (double)$item_price;
            unset($items[$order_item_id]);
        }

        //Put Customer Payment into new Combine Order


        if(empty($items)){
            $new_combine_customer_payment['value'] += $order_totals[$merged_customer_payment_id]['value'];
            $new_combine_customer_payment['tax'] += $order_totals[$merged_customer_payment_id]['tax'];
            $orderx->delete();
        }
        else{
            if($combined_items_price >= $customer_payment){
                $new_combine_customer_payment['value'] += $order_totals[$merged_customer_payment_id]['value'];
                $new_combine_customer_payment['tax'] += $order_totals[$merged_customer_payment_id]['tax'];
                unset($order_totals[$merged_customer_payment_id]);

            }
            else{
                $new_combine_customer_payment['value'] -= $combined_items_price;
                $order_totals[$merged_customer_payment_id]['value'] += $combined_items_price;
            }

            $orderx->data['items'] = $items;
            $orderx->data['order_total'] = $order_totals;
            $orderx->save();

        }

    }

    //Order Total
    array_push($combine_order_totals, $new_combine_customer_payment);
    $order->data['order_total'] = $combine_order_totals;

    //Order status

    $combine_order_status_query = database::query(
        "select os.id, osi.name from ". DB_TABLE_ORDER_STATUSES ." os
        left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (os.id = osi.order_status_id)
        where osi.name = 'Combined'
        limit 1;"
    );

    $combine_order_status = database::fetch($combine_order_status_query);
    if(!empty($combine_order_status)){
        $order->data['order_status_id'] = (int) $combine_order_status['id'];
    }
}


// Save order
$order->data['unread'] = true;
$order->save();

// Clean up combined Session
session::$data['combined_new_items'] = array();
session::$data['combine_orders'] = array();
// Send order confirmation email
if (settings::get('send_order_confirmation')) {
    $bccs = array();

    if (settings::get('email_order_copy')) {
        foreach (preg_split('#[\s;,]+#', settings::get('email_order_copy')) as $email) {
            if (empty($email)) continue;
            $bccs[] = $email;
        }
    }

    $order->email_order_copy($order->data['customer']['email'], $bccs, $order->data['language_code']);
}

// Run after process operations
$shipping->after_process($order);

$order_process = new mod_order();
$order_process->after_process($order);

header('Location: '. document::ilink('combined_order_success'));
exit;
