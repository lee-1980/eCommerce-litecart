<style>

    /* Border before Total */
    .finalAOHS td {
        border-right: none;
    }

    /* Border between Final Text and $ */
    .finalAOHS td:nth-last-child(2) {
        border-right: 0px solid black;
        text-align: right;
    }

    /* Subtotal */
    .finalAOHS#ohs_subtotal td:last-of-type {
        border-top: 0px solid black;
        border-bottom: 0px solid black;
    }

    /* Total */
    .finalAOHS#ohs_total td:last-of-type {
        border-top: 0px solid black;
    }

    /* Tracking ID */
    .finalAOHS#ohs_total td:first-of-type {
        text-align: center;
    }

    .finalAOHS#ohs_table td:last-of-type {
        border-top: 0px solid black;
    }


    .finalAOHS#ohs_custom td {
        border-top: 0px solid black;
        border-bottom: 0px solid black;
        padding: 1px 2px;
    }

    .articleOHS td:first-child {
        cursor: pointer;
    }

</style>
<div id="sidebar">
    <div id="column-left">
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_customer_service_links.inc.php'); ?>
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_account_links.inc.php'); ?>
    </div>
</div>

<div id="content">
    {snippet:notices}

    <?php echo functions::form_draw_form_begin('combine_items_form', 'post', document::href_ilink('combined_checkout')); ?>
    <section id="box-order-history" class="box">
        <h1 class="title"><?php echo language::translate('title_combine_order_items', 'Combine Order Items'); ?></h1>
        <div class="table-responsive">
            <table class="table table-striped table-hover data-table">
                <thead>
                <tr>
                    <th class="text-center"><?php echo language::translate('title_order', 'Order'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_order_status', 'Order Status'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_is_paid', 'Is Paid'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_date', 'Date'); ?></th>
                    <th class="text-center"><?php echo language::translate('title_restore', 'Restore'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($orders) foreach ($orders as $order) { ?>
                <tr>
                    <td class="text-center">
                        <a href="<?php echo htmlspecialchars($order['link']); ?>" class="lightbox-iframe">
                            <?php echo language::translate('title_order', 'Order'); ?> #<?php echo $order['id']; ?></a>
                        <?php echo functions::form_draw_hidden_field('combine_order_ids[]', $order['id']); ?>
                    </td>
                    <td class="text-center"><?php echo $order['order_status']; ?></td>
                    <td class="text-center"><?php echo $order['is_paid']; ?></td>
                    <td class="text-center"><?php echo $order['date_created']; ?></td>
                    <td class="text-center">
                        <a title="Restore" href="#" class="restore_order" data-id="<?php echo $order['id']; ?>">
                            <?php echo functions::draw_fonticon('fa-reply-all'); ?>
                        </a>
                    </td>
                </tr>
                    <!-- DETAIL's of PRODUCT's -->
                    <?php if (isset($order['items'])) { ?>
                <tr>
                    <td colspan="5">
                        <table border="1" cellpadding="0" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><?php echo language::translate('title_select_items','Select Items'); ?></th>
                                <th><?php echo language::translate('title_product_image','Product Image'); ?></th>
                                <th><?php echo language::translate('title_description_information','Description / Information'); ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
						// INIT ITEM'S
						$items = $order['items'];
                        list($product_image_width, $product_image_height) = functions::image_scale_by_width(160, settings::get('product_image_ratio'));
                        // PRINT ITEMS - ROW's
                            foreach ($items as $item) {
                            $product = new ent_product((int)$item['product_id']);
                            //var_dump($product->data['images']);
                            $image = current($product->data['images']);
                            $image_url = document::href_link(WS_DIR_APP.functions::image_thumbnail(FS_DIR_APP . 'images/' . $image['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping')));
                            $unit_price = (double) $item['price'] + $item['tax'];
                            //	echo "<pre>" . print_r($item, true) . "</pre>";
                            // DESCRIPTION Translation
                            $short_desciption = (isset($item['name'])) ? $item['name'] : language::translate('text_view_full_page', 'View full page');
                            // TOTAL per Item
                            $total = 0;// reset
                            $total = $unit_price * (int) $item['quantity'];
                            // ADD for SUBTOTAL
                            $sum += $total;
                            // PRINT

                            // GET INFOs about PRODUCT
                            $prod = new ctrl_product();
                            $prod->load((int)$item['product_id']);
                            //	echo "<pre>" . print_r($prod->data, true) . "</pre>";
                            //	echo "<pre>" . print_r($item, true) . "</pre>";

                            // SHORT_DESC Translation
                            $short_desciption = (isset($prod->data['short_description'][$_SESSION['language']['code']])) ? $prod->data['short_description'][$_SESSION['language']['code']] : "Failure while loading translation!";
                            $name = (isset($prod->data['short_description'][$_SESSION['language']['code']])) ? $prod->data['short_description'][$_SESSION['language']['code']] : "Failure while loading translation!";
                            $delivery_status_info = (isset($prod->data['delivery_status_info'])) ? $prod->data['delivery_status_info']: "Sold Out";
                            $discount_description = (isset($prod->data['discount_description'][$_SESSION['language']['code']])) ? $prod->data['discount_description'][$_SESSION['language']['code']] : "Failure while loading translation!";
                            // TOTAL pP
                            $total = 0;// reset
                            $total = $unit_price * (int) $item['quantity'];

                            // <td class="delivery_status"><?php echo $prod->data['delivery_status'];
                            ?>

                            <tr class="articleOHS">
                                <td class="text-center" style="<?php echo $order['is_paid'] == 'Paid'?'cursor: not-allowed;':'' ?>"><?php echo functions::form_draw_checkbox('sco_items[' . $order['id'] . ']['. $item['product_id'] .']', $item['product_id'], $order['is_paid'] == 'Paid'? $item['product_id']:true , $order['is_paid'] == 'Paid'?' data-property="pre-checked"':''); ?></td>
                                <td class="text-center" width='100'><?php echo '<a target="_blank" href="' . document::href_ilink('product', array('product_id' => (int)$item['product_id']), false) . '"><div><br /><img src="'. $image_url .'" alt=""/></div>';?></td>
                                <td class="text-left" >
                                    <table auto="" cellpadding="0" cellspacing="0" style="background-color: transparent;  text-align: left;"><tbody>

                                        <tr class="finalAOHS" id="ohs_custom">

                                            <td class="text-left">Name</td>
                                            <td class="text-left">: <?php echo '<a target="_blank" href="' . document::href_ilink('product', array('product_id' => (int)$item['product_id']), false) . '">' . $name . '</a>';// SHORT DESCRIPTION ?></td>
                                        </tr>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Barcode</td>
                                            <td class="text-left">: <?php echo $item['sku'];?></td>

                                        </tr>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Price</td>
                                            <td class="text-left">: <?php echo currency::format($unit_price);?></td>
                                        </tr>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Quantity</td>
                                            <td class="text-left">: <?php echo (int) $item['quantity'];?> pcs</td>
                                        </tr>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Total</td>
                                            <td class="text-left">: <?php echo currency::format(($unit_price) * $item['quantity']);?></td>
                                        </tr>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Status</td>
                                            <td class="text-left">: <?php echo $delivery_status_info; ?></td>
                                        </tr>
                                        <?php if (isset($order['tracking_id']) && $order['tracking_id'] != '') { ?>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left"> <?php echo language::translate('title_shipping_tracking_id', 'Shipping Tracking ID');?></td>
                                            <td class="text-left">: <?php echo $order['tracking_id'];?></td>
                                            <?php } ?>
                                        </tr>
                                        <?php if (isset($order['tracking_id']) && $order['tracking_id'] != '') { ?>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">Malaysia</td>
                                            <td class="text-left">: <a target="_blank" href="https://www.tracking.my/">www.tracking.my</a></td>
                                            <?php } ?>
                                        </tr>
                                        <?php if (isset($order['tracking_id']) && $order['tracking_id'] != '') { ?>
                                        <tr class="finalAOHS" id="ohs_custom">
                                            <td class="text-left">International</td>
                                            <td class="text-left">: <a target="_blank" href="https://www.pos.com.my/">www.pos.com.my</a></td>
                                            <?php } ?>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?php } ?>
						</tbody>
                        </table>
                    </td>
                </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <p>
            <button class="btn btn-default selectall" type="button" value="Save"><?php echo language::translate('title_select_all_items', 'Select All Items'); ?></button>
            <button class="btn btn-default deselectall" type="button" value="Save"><?php echo language::translate('title_deselect_all_items', 'Deselect All Items'); ?></button>
            <button class="btn btn-default_save" type="submit" name="save_details" value="Save"><?php echo language::translate('title_combine_items', 'Combine Items'); ?></button>
        </p>
    </section>
    <?php echo functions::form_draw_form_end('combine_items_form'); ?>
</div>


<script>
    var ReStoreLock = true;
    $(document.body).on('click', 'a.restore_order', function(e){
        e.preventDefault();
        var order_id = $(this).data('id');
        if(ReStoreLock) restore_order(order_id);
    });

    $(document.body).on('click', '.articleOHS td:first-child', function(e){
        if($(this).find('input:checkbox').data('property') == 'pre-checked') {
            $(this).find('input:checkbox').prop('checked', true);
        }
        else{
            if($(this).find('input:checkbox').is(':checked')){
                $(this).find('input:checkbox').prop('checked', false);
            }
            else{
                $(this).find('input:checkbox').prop('checked', true);
            }
        }

    });

    $(document.body).on('click', '.articleOHS td:first-child input:checkbox', function(e){
        if($(this).data('property') == 'pre-checked') {
            $(this).find('input:checkbox').prop('checked', true);
        }
        else{
            if($(this).is(':checked')){
                $(this).prop('checked', false);
            }
            else{
                $(this).prop('checked', true);
            }
        }
    });

    $(document.body).on('click', '.selectall', function(){
        console.log()
        $('.articleOHS td:first-child input:checkbox').each(function( i, e){
           if($(this).data('property') == 'pre-checked') return;
           $(this).prop('checked', true);
        });
    });

    $(document.body).on('click', '.deselectall', function(){
        $('.articleOHS td:first-child input:checkbox').each(function(){
            if($(this).data('property') == 'pre-checked') return;
            $(this).prop('checked', false);
        });
    });

    function restore_order(order_id){
        ReStoreLock = false;

        var url = '<?php echo document::ilink('ajax/combine_ajax'); ?>';

        var data = {
            action: 'retore_combined_order',
            order_id: order_id,
            token: '<?php echo form::session_post_token(); ?>'
        };

        if (!$('body > .loader-wrapper').length) {
            var loader = '<div class="loader-wrapper" style="position: fixed; top: 50%; left: 10%; right: 10%; text-align: center; margin-top: -128px; opacity: 0.1; z-index: 999999;">'
                + '  <div class="loader" style="width: 256px; height: 256px;"></div>'
                + '</div>';
            $('body').append(loader);
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
            success: function(response) {
                var res = JSON.parse(response);
                if(res.status && res.status == 'success'){
                    $('a[data-id="' + order_id + '"]').closest('tr').fadeTo('fast', 0.1);
                    $('a[data-id="' + order_id + '"]').closest('tr').next().fadeTo('fast', 0.1);
                    $('a[data-id="' + order_id + '"]').closest('tr').next().remove();
                    $('a[data-id="' + order_id + '"]').closest('tr').remove();

                }

                $('body > .loader-wrapper').fadeOut('fast', function(){
                    $(this).remove();
                });
                ReStoreLock = true;
            }
        });
    }
</script>