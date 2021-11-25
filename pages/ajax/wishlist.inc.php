<?php

header('X-Robots-Tag: noindex');

if(!isset(session::$data['wishlist-changable'])){
    session::$data['wishlist-changable'] = array();
}
if(!empty(customer::$data['id'])){
    try{
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $response = '';
            $wishlist_query = database::query(
                "select * from ". DB_TABLE_WISHLIST ." where product_id = '". (int)$_POST['id'] ."' and customer_id = '" . (int)customer::$data['id'] ."' limit 1;"
            );
            if($wishlist = database::fetch($wishlist_query)){
                database::query(
                    "delete from " . DB_TABLE_WISHLIST . " where product_id = '" . (int)$_POST['id'] . "' and customer_id = " . (int)customer::$data['id'] . " limit 1;"
                );
                $response = '200removed!';
            }
            else{
                database::query(
                    "insert into ". DB_TABLE_WISHLIST ."
            (product_id, customer_id)
            values (". (int)$_POST['id'] .", '". (int)customer::$data['id'] ."');"
                );
                $response = '200pushed!';
            }

            $wishlist_query = database::query(
                "select * from ". DB_TABLE_WISHLIST ." where  customer_id = '" . (int)customer::$data['id'] ."';"
            );
            $wishlists = array();
            while($wishlist = database::fetch($wishlist_query)){
                array_push($wishlists, $wishlist['product_id']);
            }

            session::$data['wishlist-changable'] = $wishlists;
            echo json_encode(array('status' => $response));
        }
        else{
            throw new Exception('Product ID is not correct!');
        }
    }
    catch (Exception $e){
        echo json_encode(array('status' => 'error','data' => $e->getMessage()));
    }

}
else{
    if (!isset(session::$data['wishlist']) || !is_array(session::$data['wishlist'])) {
        session::$data['wishlist'] = array();
    }
    try{
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $response = '';
            if(in_array($_POST['id'], session::$data['wishlist'])){
                $response = '200removed!';
                session::$data['wishlist'] = \array_diff(session::$data['wishlist'], [$_POST['id']]);
            }
            else{
                $response = '200pushed!';
                array_push(session::$data['wishlist'], $_POST['id']);
            }
            session::$data['wishlist-changable'] = session::$data['wishlist'];
            echo json_encode(array('status' => $response));
        }
        else{
            throw new Exception('Product ID is not correct!');
        }
    }
    catch (Exception $e){
        echo json_encode(array('status' => 'error','data' => $e->getMessage()));
    }
}


