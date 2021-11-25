<?php

header('X-Robots-Tag: noindex');

if (!empty($_POST['remove_combined_item']) && isset(session::$data['combined_new_items']['items'][$_POST['remove_combined_item']])) {

    $product_id = session::$data['combined_new_items']['items'][$_POST['remove_combined_item']]['product_id'];

    if(!empty($product_id)){
        foreach(session::$data['combined_new_items']['sco_items'] as $order_id => $products){
            if(isset($products[$product_id])) unset(session::$data['combined_new_items']['sco_items'][$order_id][$product_id]);
        }
    }
    unset(session::$data['combined_new_items']['items'][$_POST['remove_combined_item']]);

    session::$data['combined_new_items']['total_value'] = 0;
    session::$data['combined_new_items']['total_tax'] = 0;
    foreach (session::$data['combined_new_items']['items'] as $item) {
        $num_items = $item['quantity'];

        if (!empty($item['quantity_unit']['separate'])) {
            $num_items = 1;
        }

        session::$data['combined_new_items']['total_value'] += $item['price'] * $item['quantity'];
        session::$data['combined_new_items']['total_tax'] += $item['tax'] * $item['quantity'];
    }

    header('Location: '. $_SERVER['REQUEST_URI']);
    exit;
}

if (empty(session::$data['combined_new_items']['items'])) {
    echo '<p><em>'. language::translate('description_no_combined_items', 'There are no combined Items.') .'</em></p>' . PHP_EOL
        . '<p><a href="'. document::href_ilink('combine_order') .'">&lt;&lt; '. language::translate('title_back', 'Back') .'</a></p>';
    return;
}

$box_checkout_cart = new ent_view();

$box_checkout_cart->snippets = array(
    'items' => array(),
    'subtotal' => session::$data['combined_new_items']['total_value'],
    'subtotal_tax' =>session::$data['combined_new_items']['total_tax'],
);

foreach (session::$data['combined_new_items']['items'] as $key => $item) {
    $box_checkout_cart->snippets['items'][$key] = array(
        'product_id' => $item['product_id'],
        'link' => document::ilink('product', array('product_id' => $item['product_id'])),
        'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $item['image'], 320, 320, 'FIT_USE_WHITESPACING'),
        'name' => $item['name'],
        'sku' => $item['sku'],
        'gtin' => $item['gtin'],
        'taric' => $item['taric'],
        'options' => array(),
        'display_price' => customer::$data['display_prices_including_tax'] ? $item['price'] + $item['tax'] : $item['price'],
        'price' => $item['price'],
        'tax' => $item['tax'],
        'tax_class_id' => $item['tax_class_id'],
        'quantity' => (float)$item['quantity'],
        'quantity_unit' => $item['quantity_unit'],
        'weight' => (float)$item['weight'],
        'weight_class' => $item['weight_class'],
        'dim_x' => (float)$item['dim_x'],
        'dim_y' => (float)$item['dim_y'],
        'dim_z' => (float)$item['dim_z'],
        'dim_class' => $item['dim_class'],
        'error' => $item['error'],
    );

    if (!empty($item['options'])) {
        foreach ($item['options'] as $k => $v) {
            $box_checkout_cart->snippets['items'][$key]['options'][] = $k .': '. $v;
        }
    }
}

echo $box_checkout_cart->stitch('views/combined_box_checkout_cart');

