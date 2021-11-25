<?php

header('X-Robots-Tag: noindex');


if(!empty($_POST['order_id']) && !empty($_POST['item_ids']) && is_array($_POST['item_ids'])){

    $sold_out_items = array();
    $order = new ent_order($_POST['order_id']);
    $language_code = $order->data['language_code'];
    try{
        $aliases = array(
            '%order_id' => $order->data['id'],
            '%firstname' => $order->data['customer']['firstname'],
            '%lastname' => $order->data['customer']['lastname'],
            '%store_name' => settings::get('store_name'),
            '%store_url' => document::ilink('', array(), false, array(), $language_code),
        );

        $aliases['%order_items'] = '<table border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%; text-align:center; background-color: #e8c48675;">
                    <thead>
                    <tr style="background-image: url(https://ittoysline.com/images/customize/Gold%20Brush%20Table.png);" >
                        <th style="color:black; padding: 10px 12px; border-left: 1px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Product Image</th>
                        <th style="color:black; padding: 10px 12px; border-left: 0px solid; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid #000000;">Product Name</th>
                    </tr>
                    </thead><tbody>';
        list($product_image_width, $product_image_height) = functions::image_scale_by_width(180, settings::get('product_image_ratio'));

        foreach ($_POST['item_ids'] as $product_id){

            $product = new ent_product($product_id);
            $product->data['quantity'] = 0;
            $product->save();
            array_push($sold_out_items, $product_id);

            $image = current($product->data['images']);
            $image_url = document::href_link(WS_DIR_APP.functions::image_thumbnail(FS_DIR_APP . 'images/' . $image['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping')));
            $img = '<img src="'. $image_url .'" />';

            $aliases['%order_items'] .= '
                <tr>
                  <td style="padding: 0px 0px; text-align: center; color: #000000; border-left: 1px solid; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid" >
                  <a href="'. document::href_ilink('product', array('product_id' => $product_id)) .'">
                  '. $img .'
                  </a></td>
                  <td style="padding: 1px 0px 26px; border-right: 1px solid; border-top: 0px solid; border-bottom: 1px solid; color:black ">
                    <table auto="" border="0" cellpadding="1" cellspacing="1" style="background-color: transparent;  text-align: left;">
                      <tbody>
                       <tr align="left">
                         <td valign="top">
                            <span style="color: black; font-size:11pt"> ' . $product->data['name'][language::$selected['code']] . ' </span>
                         </td>    
                       </tr>
                     </tbody>
                     </table>
                </td>
                </tr>';
        }

        $aliases['%order_items'] .= '</tbody></table>';
        if(count($sold_out_items) > 0) {

            $subject = '['. language::translate('title_order_items_changed', 'Order Items Changed', $language_code).']';

            $message = "Dear %firstname %lastname \r\n\r\n";
            $message .= "Your order #%order_id was updated due to the following items were out of stock:\r\n\r\n"
                . "%order_items\r\n\r\n"
                . "Regards,\r\n\r\n"
                . "%store_name\r\n\r\n"
                . "%store_url\r\n";

            $message = strtr(language::translate('email_order_sold_out_change', $message, $language_code), $aliases);

            $email = new ent_email();
            $recipient = $order->data['customer']['email'];

            $email->add_recipient($recipient)
                ->set_subject($subject)
                ->add_body($message, true)
                ->send();

            echo json_encode(array('status' => 'success', 'message' => 'Applying Sold out to items were done successfully!', 'item_list' => $sold_out_items));
        }
    }
    catch (Exception $e){
        echo json_encode(array('status' => 'error', 'message' =>$e->getMessage(), 'item_list' => $sold_out_items));
    }
}
else{
    echo json_encode(array('status' => 'error', 'message' => 'missing parameters!'));
}
