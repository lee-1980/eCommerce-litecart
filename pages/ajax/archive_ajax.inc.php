<?php
header('X-Robots-Tag: noindex');

if(empty(customer::$data['id']) || !isset($_POST['action']) || $_POST['token'] != form::session_post_token()){
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

try {

    if (!isset($_POST['order_id'])) {
        throw new Exception('Order Id is missing');
    }

    if (!isset($_POST['action'])) {
        throw new Exception('Undefined Action');
    }

    if ($_POST['action'] == 'restore') {

        database::query(
            "update " . DB_TABLE_ORDERS . "
            set archived = 0
            where id = " . (int)$_POST['order_id'] . "
            limit 1;"
        );

        echo json_encode(array('status' => 'success'));

    } else if ($_POST['action'] == 'archive') {

        database::query(
            "update " . DB_TABLE_ORDERS . "
            set archived = 1
            where id = " . (int)$_POST['order_id'] . "
            limit 1;"
        );

        echo json_encode(array('status' => 'success'));
    }

}
catch (Exception $e){
    echo json_encode(array('status' => 'error', 'message' =>$e->getMessage()));
}