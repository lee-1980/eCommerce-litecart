<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php

if(empty(customer::$data['id']) || !isset($_POST['save_details']) || $_POST['token'] != form::session_post_token() || empty($_POST['combine_order_ids'])){
    header('Location: '. document::href_ilink('combine_order'));
    exit;
}

$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));

if (session::$data['LastRequest'] == $RequestSignature)
{
    $page_refresh = true;
}
else
{
    session::$data['LastRequest'] = $RequestSignature;
    $page_refresh = false;
}

header('X-Robots-Tag: noindex');

document::$layout = 'checkout';

document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
document::$snippets['title'][] = language::translate('combine_order_checkout:head_title', 'Combine Order Checkout');

customer::require_login();

try{
    $_page = new ent_view();

    if(!$page_refresh) {

        $combine_items = array();
        session::$data['combined_new_items'] = array(
            'items' => 0,
            'total_value' => 0,
            'total_tax' => 0,
            'sco_items' => $_POST['sco_items']
        );


        foreach ($_POST['combine_order_ids'] as $order_id) {
            if (!isset($_POST['sco_items'][$order_id])) continue;

            $orderx = new ctrl_order($order_id);
            $items = (isset($orderx->data['items'])) ? $orderx->data['items'] : [];
            $product_ids = array_column($items, 'product_id', 'id');

            foreach ($_POST['sco_items'][$order_id] as $combined_item_id) {

                $order_item_id = array_search($combined_item_id, $product_ids);

                if (!empty($order_item_id)) {

                    $product = reference::product($combined_item_id);

                    if (!empty($product->quantity_unit['separate'])) {
                        $item_key = uniqid();
                    } else {
                        $item_key = md5(serialize(array($product->id, $options)));
                    }

                    $item = array(
                        'id' => null,
                        'product_id' => (int)$combined_item_id,
                        'options' => $items[$order_item_id]['options'],
                        'option_stock_combination' => $items[$order_item_id]['option_stock_combination'],
                        'image' => $product->image,
                        'name' => $product->name,
                        'code' => $product->code,
                        'sku' => $product->sku,
                        'mpn' => $product->mpn,
                        'gtin' => $product->gtin,
                        'taric' => $product->taric,
                        'price' => $items[$order_item_id]['price'],
                        'extras' => 0,
                        'tax' => $items[$order_item_id]['tax'],
                        'tax_class_id' => $product->tax_class_id,
                        'quantity' => $items[$order_item_id]['quantity'],
                        'quantity_unit' => array(
                            'name' => !empty($product->quantity_unit['name']) ? $product->quantity_unit['name'] : '',
                            'decimals' => !empty($product->quantity_unit['decimals']) ? $product->quantity_unit['decimals'] : '',
                            'separate' => !empty($product->quantity_unit['separate']) ? $product->quantity_unit['separate'] : '',
                        ),
                        'weight' => $items[$order_item_id]['weight'],
                        'weight_class' => $items[$order_item_id]['weight_class'],
                        'dim_x' => $items[$order_item_id]['dim_x'],
                        'dim_y' => $items[$order_item_id]['dim_y'],
                        'dim_z' => $items[$order_item_id]['dim_z'],
                        'dim_class' => $items[$order_item_id]['dim_class'],
                        'error' => '',
                    );

                    if (isset($combine_items[$item_key])) {
                        $combine_items[$item_key]['quantity'] += $items[$order_item_id]['quantity'];
                    } else {
                        $combine_items[$item_key] = $item;
                    }
                }
            }
        }

        foreach ($combine_items as $item) {
            $num_items = $item['quantity'];
            if (!empty($item['quantity_unit']['separate'])) {
                $num_items = 1;
            }
            session::$data['combined_new_items']['total_value'] += $item['price'] * $item['quantity'];
            session::$data['combined_new_items']['total_tax'] += $item['tax'] * $item['quantity'];
        }
        session::$data['combined_new_items']['items'] = $combine_items;
    }
}
catch (Exception $exception){
    notices::add('errors', $e->getMessage());
}

echo $_page->stitch('pages/combined_checkout');