
      <?php if(!empty($admin_customerList)) {} else{?>
       <div id="sidebar">
         <div id="column-left">
           <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_customer_service_links.inc.php'); ?>
           <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_account_links.inc.php'); ?>
         </div>
       </div>
      <?php } ?>
      






<div id="content">
  {snippet:notices}

  <section id="box-order-history" class="box">

    <h1 class="title"><?php echo language::translate('title_order_history', 'Order History'); ?></h1>

		   <?php if (customer::$data->date_valid_to > date('Y-m-d H:i:s')) { ?>	
		   <?php echo language::translate('title_discount_code','Discount Code'); ?> : <?php echo (customer::$data['discount_code_info']); ?>
           <?php } else if (customer::$data->date_valid_from > date('Y-m-d H:i:s')) { ?>	
           <?php echo language::translate('title_discount_code','Discount Code'); ?> :
           <?php } ?>
		   </br>
		   </br>
      
   

    
    <div class="table-responsive">
      <table class="table table-striped table-hover data-table">
        <thead>
        <tr>
          <th class="main"><?php echo language::translate('title_order', 'Order'); ?></th>
          <th class="text-center"><?php echo language::translate('title_order_status', 'Order Status'); ?></th>
          <th class="text-left"><?php echo language::translate('title_outstanding', 'Outstanding'); ?></th>
          <th class="text-left"><?php echo language::translate('title_date', 'Date'); ?></th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($orders) foreach ($orders as $order) { ?>
        <tr>
          <td><a href="<?php echo htmlspecialchars($order['link']); ?>" class="lightbox-iframe"><?php echo language::translate('title_order', 'Order'); ?> No: <?php echo $order['id']; ?><?php echo str_repeat('&nbsp;', 9); ?> <a href="#" title="Archive" class="archive_order" data-id="<?php echo $order['id']; ?>"><?php echo functions::draw_fonticon('fa-archive'); ?>&nbsp;Archive</a><?php if(!empty($order['is_combine']) && $order['is_combine'] == '1' ) { echo str_repeat('&nbsp;', 9); ?><a href="#" title="Combine" class="combine_order" data-id="<?php echo $order['id']; ?>"><?php echo functions::draw_fonticon('fa-clone'); ?>&nbsp;Combine Order</a></td><?php } ?>
          <td class="text-center"><?php echo $order['order_status']; ?></td>
          <td class="text-right"><?php echo $order['payment_due']; ?></td>
          <td class="text-right"><?php echo $order['date_created']; ?></td>
          
	   <td class="text-right">
	   <a title="Print" href="<?php echo htmlspecialchars($order['printable_link']); ?>" target="_blank"><?php echo functions::draw_fonticon('fa-print'); ?></a>
	   
	   </td>
		</td>
      </tr>
	  
	<!-- DETAIL's of PRODUCT's -->
	<?php if (isset($order['items'])) {?>
	  <tr>
		<td colspan="5">
			<details <?php if (!isset($firstRound) || $firstRound == false) echo "open";?>>
				<summary><?php echo language::translate('title_products','Products'); ?></summary>
					<table class="OrderHistorySummary table table-striped table-hover data-table">
						<thead>
							<tr>
							    <th><?php echo language::translate('title_product_image','Product Image'); ?></th>
								
								<th><?php echo language::translate('title_description_information','Description / Information'); ?></th>
								
								
							</tr>
						</thead>
						<tbody>
						<?php 
						// INIT ITEM'S
						$items = $order['items'];
						
						$firstRound = false;
						$sum = 0;
						
						//echo "<pre>" . print_r($order["order_totals"] , true) . "</pre>";
						
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
							    <td class="text-center" width='100'><?php echo '<a target="_blank" href="' . document::href_ilink('product', array('product_id' => (int)$item['product_id']), false) . '"><div><br /><img src="'. $image_url .'" alt=""/></div>';?></td>
								<td class="text-left" >
                                <table auto="" border="0" cellpadding="0" cellspacing="0" style="background-color: transparent;  text-align: left;"><tbody>
                                
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
                
               
                                
						<!-- Subtotal -->
						
							<tr class="finalAOHS" id="ohs_subtotal" >
								<td class="text-right" colspan="0"><?php echo language::translate('title_subtotal', 'Subtotal');?> :</td>
								<td class="text-left"><?php echo currency::format($sum); ?></td>
							</tr>
						
						<!-- Fee's -->
						<?php 
							$fee1 = $order['shipping_fee'];
							$fee2 = $order['payment_fee'];
							$fee3 = $order['customer_payment'];
							$fee4 = $order['installment_fee'];
							$fee5 = $order['paid_fee'];
							$fee6 = $order['discount_code'];
							$fee7 = $order['guest_discount'];
							$fee8 = $order['customer_discount'];
							
							foreach ($order["order_totals"] as $fee) { 
								$fees += $fee['value'] + $fee1['value'] + $fee2['value'] + $fee3['value'] + $fee6['value'] + $fee7['value'] + $fee8['value'] +$fee['tax'];
						?>
							<tr class="finalA" id="ohs_fees">
								<td class="text-right" colspan="1"><?php echo $fee['title']; ?> :</td>
								<td class="text-left"><?php echo !empty(customer::$data['display_prices_including_tax']) ? currency::format($fee['value'] + $fee['tax'], false) : currency::format($fee['value'], false); ?></td>
							</tr>
							<?php }	?>


						
						<!-- Total -->
							<tr class="finalAOHS" id="ohs_total">
								
								<td class="text-right"><strong><?php echo language::translate('title_grand_total', 'Grand Total');?> :</strong></td>
								<td class="text-left"><strong><?php echo currency::format(($sum + $fee1 + $fee2)); ?></strong></td>
							</tr>
								<tr class="finalAOHS" id="ohs_total">
								
								<td class="text-right"><?php echo language::translate('title_payment_made', 'Payment Made');?> :</td>
								<td class="text-left"><?php echo currency::format(($fee3) + ($fee7) + ($fee6) + ($fee8)); ?></td>
							</tr>
								<tr class="finalAOHS" id="ohs_total">
								
								<td class="text-right"><?php echo language::translate('title_outstanding', 'Outstanding');?> :</td>
								<td class="text-left"><?php echo currency::format((($sum + $fee1 + $fee2) - ($fee3) - ($fee6) - ($fee8))); ?></td>
							</tr>
							
						</tbody>
					</table>
			</details>
		</td>
	  </tr>
      <?php }} ?>
      </tbody>
    </table>
	<style>
	
		/* DETAILs */
		details summary {
			padding-left: 15px;
		}
		
		/* TABLE of Products */
			/* thead */
			.OrderHistorySummary thead th {
				border-right: 1px solid black;
			}
		
			/* scope-border */ 
			.OrderHistorySummary {
				border: 1px solid black;
			}
		
			/* Orient last columnt to rigth (Money values) */
			.articleOHS td:last-of-type,
			.articleOHS td:nth-last-child(2),
			.finalAOHS {
				text-align: right;
			}
			
			
			
			/* Articles */
				.articleOHS {
					border-bottom: 1px solid black;
					line-height: 0px;
				}
				
				/* column borders */
				.articleOHS td {
					border-right: 1px solid black;
				}
			
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
			

	</style>  
	<script>
    var ArchiveLock = true;
    $(document.body).on('click', 'a.archive_order', function(e){
        e.preventDefault();
        var order_id = $(this).data('id');
        if(ArchiveLock) restore_order(order_id);
    });

    function restore_order(order_id){
        ArchiveLock = false;

        var url = '<?php echo document::ilink('ajax/archive_ajax'); ?>';

        var data = {
            action: 'archive',
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
                ArchiveLock = true;
            }
        });
    }

    var CombineLock = true;
    $(document.body).on('click', 'a.combine_order', function(e){
        e.preventDefault();
        var order_id = $(this).data('id');
        if(CombineLock) combine_order(order_id);
    });

    function combine_order(order_id){
        CombineLock = false;

        var url = '<?php echo document::ilink('ajax/combine_ajax'); ?>';

        var data = {
            action: 'combine',
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
            console.log(response);
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
                CombineLock = true;
            }
        });
    }
</script>
      






























    </div>

    <?php echo $pagination; ?>
  </section>
</div>
