<?php

header('X-Robots-Tag: noindex');


if(empty(customer::$data['id']) || !isset($_POST['action']) || $_POST['token'] != form::session_post_token()){
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

if(!isset(session::$data['combine_orders']) || !is_array(session::$data['combine_orders'])){
    session::$data['combine_orders'] = array();
}

if(!empty(customer::$data['id']) && $_POST['action'] == 'combine'){

    $pos = array_search( $_POST['order_id'], session::$data['combine_orders']);
    if(empty($pos)) array_push(session::$data['combine_orders'], $_POST['order_id']);

    echo json_encode(array('status' => 'success'));
}
else if(!empty(customer::$data['id']) && $_POST['action'] == 'retore_combined_order'){

    $pos = array_search( $_POST['order_id'], session::$data['combine_orders']);
    unset(session::$data['combine_orders'][$pos]);
    echo json_encode(array('status' => 'success'));

}

exit;