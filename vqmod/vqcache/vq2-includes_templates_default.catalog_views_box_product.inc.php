<article id="box-product" class="box" data-id="<?php echo $product_id; ?>" data-sku="<?php echo htmlspecialchars($sku); ?>" data-name="<?php echo htmlspecialchars($name); ?>" data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>">

  <div class="row">
    <div class="col-sm-4 col-md-6">

      
      <?php if (!empty(customer::$data['id']) || (empty(reference::product($product_id)->hidden))) { ?>	
      

      
      <?php if (!empty(customer::$data['id']) || (empty(reference::product($product_id)->hide_product))) { ?>	
      

      
      <?php if ((!empty(customer::$data['id']) && (empty(customer::$data['enable_hide_product'])) || (empty(reference::product($product_id)->hide_product)))) { ?>	
      
      <div class="images row">

        <div class="col-xs-12">
          
      <?php if (empty(customer::$data['forbidden'] == 0) || (empty(reference::product($product_id)->forbidden))) { ?>
          <a class="main-image thumbnail" href="<?php echo document::href_link(WS_DIR_APP . $image['original']); ?>" data-toggle="lightbox" data-gallery="product">
            <img class="img-responsive" src="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?>" srcset="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?> 1x, <?php echo document::href_link(WS_DIR_APP . $image['thumbnail_2x']); ?> 2x" alt="" title="<?php echo htmlspecialchars($name); ?>" />
          </a>
        </div>
        
        <?php } else { ?>
        <?php if (empty(customer::$data['forbidden'] == 1) || (!empty(customer::$data['id'])) || (empty(reference::product($product_id)->forbidden))) { ?>
        <div class="image-wrapper">
          <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" />
        </div>
        </div>        
        <?php } ?>
        <?php } ?>

	  <div class="col-xs-12">
      <div style="text-align: center;">
      <?php echo language::translate('title_photos_are_of', 'Photos are of a prototype and the actual product may differ'); ?>
      </div>
      </div>

        <?php foreach ($extra_images as $image) { ?>
        <div class="col-xs-4">
            <?php if (empty(customer::$data['forbidden'] == 0) || (empty(reference::product($product_id)->forbidden))) { ?>
          <a class="extra-image thumbnail" href="<?php echo document::href_link(WS_DIR_APP . $image['original']); ?>" data-toggle="lightbox" data-gallery="product">
            <img class="img-responsive" src="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?>" srcset="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?> 1x, <?php echo document::href_link(WS_DIR_APP . $image['thumbnail_2x']); ?> 2x" alt="" title="<?php echo htmlspecialchars($name); ?>" />
          </a>
        <?php } else { ?>
        <?php if (empty(customer::$data['forbidden'] == 1) || (!empty(customer::$data['id'])) || (empty(reference::product($product_id)->forbidden))) { ?>
        <div class="extra-image">
		<img class="img-responsive" src="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" srcset="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" alt="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" title="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" />
        </div>
        <?php } ?>
        <?php } ?>
        </div>
        <?php } ?>
      </div>

      














    </div>

    <div class="col-sm-8 col-md-6">
      
            
    <?php error_reporting(0); ?>
    <h1 class="title"><?php echo nl2br($name); 
    if (user::$data['status']) {
        echo ' <a title="Edit Product" target="_blank" href="' .document::link(WS_DIR_ADMIN, array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => $product_id)) . '">&nbsp;<span style="color: blue;"></br><i class="fa fa-cog"></i></a>';
    }
    if (user::$data['status']) {
    echo ' <a title="Clone Product" target="_blank" href="' . document::link(WS_DIR_ADMIN, array('app' => 'catalog', 'doc' => 'edit_product', 'clone_id' => $product_id)) .'">&nbsp;<span style="color: green;"><i class="fa fa-copy"></i></a>'; 
    }
    
    
    ?>
    <?php if (!empty(user::$data['status'])) { ?>
    
    <?php echo $product['wishlist_customer'] ?>

    <?php if (!empty(reference::product($product_id)->oversize_parcel)) { ?>
    &nbsp;<span style="color: #a15300;"><i class="fa fa-dropbox"></span></i>
    <?php } ?>  

    <?php if (!empty(reference::product($product_id)->keywords)) { ?>
    &nbsp;<span style="color: #9500ff;"><i class="fa fa-key"></span></i>
    <?php } ?>  
    
    <?php if (!empty(reference::product($product_id)->attributes)) { ?>
    &nbsp;<span style="color: #ffae0d;"><i class="fa fa-info-circle"></span></i>
    <?php } ?>    
    
    <?php if (!empty(reference::product($product_id)->webp)) { ?>
    &nbsp;<span style="color: black;"><i class="fa fa-image"></span></i>     
    <?php } ?>

    <?php if (!empty(reference::product($product_id)->free_shipping)) { ?>
    &nbsp;<span style="color: #69004e;"><i class="fa fa-truck"></span></i>
    <?php } ?>   

    <?php if (!empty(reference::product($product_id)->no_free_shipping)) { ?>
    &nbsp;<span style="color: #ff0059;"><i class="fa fa-truck"></span></i>
    <?php } ?>  
    
    </br>

    <?php if (!empty(reference::product($product_id)->specialprice)) { ?>
    &nbsp;<span style="color: #ffee00;"><i class="fa fa-star"></span></i>
    <?php } ?>   
    
    <?php if (!empty(reference::product($product_id)->customer_specialprice)) { ?>
    &nbsp;<span style="color: #23d400;"><i class="fa fa-star"></span></i>
    <?php } ?>  
    
    <?php if (!empty(reference::product($product_id)->wholesale_specialprice)) { ?>
    &nbsp;<span style="color: #0055d4;"><i class="fa fa-star"></span></i>
    <?php } ?>      
   
    <?php if (!empty(reference::product($product_id)->insaneprice)) { ?>
    &nbsp;<span style="color: red;"><i class="fa fa-star"></span></i>
    <?php } ?> 
    
    <?php if (!empty(reference::product($product_id)->no_customer_group_prices)) { ?>
    &nbsp;<span style="color: black;"><i class="fa fa-star"></span></i>
    <?php } ?>  
    
   
    <?php } ?>
    </h1>
    
    <div>
            

      
      <!-- NONE -->
      	





      <?php if (!empty($manufacturer)) { ?>
      <div class="manufacturer">
        <a href="<?php echo htmlspecialchars($manufacturer['link']); ?>">
          <?php if ($manufacturer['image']) { ?>
          <img src="<?php echo document::href_link(WS_DIR_APP . $manufacturer['image']['thumbnail']); ?>" srcset="<?php echo document::href_link(WS_DIR_APP . $manufacturer['image']['thumbnail']); ?> 1x, <?php echo document::href_link(WS_DIR_APP . $manufacturer['image']['thumbnail_2x']); ?> 2x" alt="<?php echo htmlspecialchars($manufacturer['name']); ?>" title="<?php echo htmlspecialchars($manufacturer['name']); ?>" />
          <?php } else { ?>
          <h3><?php echo $manufacturer['name']; ?></h3>
          <?php } ?>
        </a>
      </div>
      <?php } ?>

      
      <!-- NONE -->
      	





      <?php if ($sku || $mpn || $gtin) { ?>
      <div class="codes" style="margin: 1em 0;">

      <?php if ($short_description) { ?>
        <?php echo language::translate('title_short_name', 'Name:'); ?>&nbsp;<?php echo $short_description; ?>
      <?php } ?>

      	
        <?php if ($sku) { ?>
        <div class="sku">
          <?php echo language::translate('title_sku', 'SKU'); ?>:
          <span class="value"><?php echo $sku; ?></span>
        </div>
        <?php } ?>

        <?php if ($mpn) { ?>
        <div class="mpn">
          <?php echo language::translate('title_mpn', 'MPN'); ?>:
          <span class="value"><?php echo $mpn; ?></span>
        </div>
        <?php } ?>


      <?php echo nl2br($medium_description); ?>
      </br>
      <label><?php echo language::translate('title_dimensions', 'Dimension'); ?>: </label>      
        <?php echo strtr("%length x %width x %height %class", array(
          '%length' => (float)reference::product($product_id)->dim_x,
          '%width' => (float)reference::product($product_id)->dim_y,
          '%height' => (float)reference::product($product_id)->dim_z,
          '%class' => reference::product($product_id)->dim_class,
        ));
      ?>

     <?php if (!empty(user::$data['status']) || (customer::$data['id'] == 4640) || (customer::$data['id'] == 4653)){ ?>
     <?php error_reporting(0); ?>
     </br>
     Weight: <?php echo weight::format(reference::product($product_id)->weight, reference::product($product_id)->weight_class ); ?>
     </br>
     Quantity: <?php echo $quantity; ?> pcs
     </br>
     Keywords: <?php echo implode (", ", $keywords); ?>
    <?php } ?>       

      	
        <?php if ($gtin) { ?>
        <div class="gtin">
          <?php echo language::translate('title_gtin', 'GTIN'); ?>:
          <span class="value"><?php echo $gtin; ?></span>
        </div>
        <?php } ?>
      </div>
      <?php } ?>

      <div class="stock-status" style="margin: 1em 0;">
       
	  
		<?php if (!empty(reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && reference::product($product_id)->preorderable)) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#c26b00"><?php echo language::translate('title_order_closed', 'Order Closed'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?>  	

         <?php } else if ((!empty(reference::product($product_id)->date_valid_from <=> date('Y-m-d H:i:s') && ($quantity <= 0 || $orderable)))) { ?>
           <div class="stock-unavailable">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#FF0000"><?php echo language::translate('title_sold_out', 'Sold Out'); ?></font></span>
          </div>

        <?php } else if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices))) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#FF0000"><?php echo language::translate('title_sold_out', 'Sold Out'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 

        <?php } else if (!empty(reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && reference::product($product_id)->pending_guest)) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#1E74FF"><?php echo language::translate('title_pending', 'Pending'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 
 
        <?php } else if (!empty(reference::product($product_id)->date_valid_to < date('Y-m-d H:i:s') && reference::product($product_id)->prebackorder)) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#0241B2"><?php echo language::translate('title_prebackorder', 'Pre-Backorder'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 
        
        <?php } else if (!empty(reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->preorderable)) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#0241B2"><?php echo language::translate('title_preorder', 'Pre-Order'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 
        
        <?php } else if (!empty(reference::product($product_id)->date_valid_to < date('Y-m-d H:i:s') && reference::product($product_id)->newarrival)) { ?>
          <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#009900"><?php echo language::translate('title_available', 'Available'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?>  

         <?php } else if (!empty(reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->backorder)) { ?>
           <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#732C00"><?php echo language::translate('title_backorder', 'Backorder'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 
        
         <?php } else if ($backorder && (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s'))) { ?>
           <div class="stock-available">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><font color="#c26b00"><?php echo language::translate('title_order_closed', 'Order Closed'); ?></font></span>
          </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?> 
        
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?>
        
        <?php } else if ($quantity > 0) { ?>
      
        <div class="stock-available">
          <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
          <span class="value"><?php echo $stock_status; ?></span>
        </div>
        <?php if ($delivery_status) { ?>
        <div class="stock-delivery">
          <?php echo language::translate('title_delivery_status', 'Delivery Status'); ?>:
          <span class="value"><?php echo $delivery_status; ?></span>
        </div>
        <?php } ?>
       <?php } else { ?>
        <?php if ($sold_out_status) { ?>
          <div class="<?php echo $orderable ? 'stock-partly-available' : 'stock-unavailable'; ?>">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><?php echo $sold_out_status; ?></span>
          </div>
        <?php } else { ?>
          <div class="stock-unavailable">
            <?php echo language::translate('title_stock_status', 'Stock Status'); ?>:
            <span class="value"><?php echo language::translate('title_sold_out', 'Sold Out'); ?></span>
          </div>
        <?php } ?>
       <?php } ?>
      </div>


      <div style="margin:0 0 1em 0; color:#CC0000;">

       <?php  if (!empty($date_valid_from_closing) && (!empty($date_valid_to_closing)) ) { ?>
		<?php  if ($quantity <= 0 ) { ?>
		</br>
	 	 <strong><?php echo language::translate('title_unfortunately_this_item', 'Unfortunately this item'); ?> </strong>
		<?php } ?>      

        <?php if (!empty(reference::product($product_id)->backorder) || ((!empty(reference::product($product_id)->prebackorder) && (!empty(reference::product($product_id)->preorderable)) && (!empty(reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s'))))) ) { ?>
         </br><strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo nl2br($date_valid_from_closing); ?></strong>

        <?php } else if ((!empty(reference::product($product_id)->prebackorder) && (!empty(reference::product($product_id)->preorderable)) && (!empty(reference::product($product_id)->date_valid_to >= date('Y-m-d H:i:s'))))) { ?>
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo nl2br($date_valid_to_closing); ?></strong>

        <?php } else if (!empty(reference::product($product_id)->backorder) || (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s'))))) { ?>   
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo nl2br($date_valid_from_closing); ?></strong>

        <?php } else if (!empty(reference::product($product_id)->backorder) || (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s'))))) { ?>   
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo nl2br($date_valid_from_closing); ?></strong>
        </br>
        <strong><?php echo language::translate('title_unfortunately_preorder', 'Unfortunately Pre-order'); ?> </strong> 

        <?php } else if (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder))) { ?>
         <strong><?php echo language::translate('title_unfortunately_preorder', 'Unfortunately Pre-order'); ?> </strong> 
       <?php } ?>      
       <?php } ?>

    <?php if (empty($date_valid_from_closing)) { ?>  
		<?php  if ($quantity <= 0 ) { ?>
		</br>
	 	 <strong><?php echo language::translate('title_unfortunately_this_item', 'Unfortunately this item'); ?> </strong>
		<?php } ?>      

        <?php if (!empty(reference::product($product_id)->backorder) || ((!empty(reference::product($product_id)->prebackorder) && (!empty(reference::product($product_id)->preorderable)) && (!empty(reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s'))))) ){ ?>
         </br><strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo language::strftime(language::$selected['format_date'], strtotime('-1 day', strtotime(reference::product($product_id)->date_valid_from))); ?></strong>

        <?php } else if ((!empty(reference::product($product_id)->prebackorder) && (!empty(reference::product($product_id)->preorderable)) && (!empty(reference::product($product_id)->date_valid_to >= date('Y-m-d H:i:s'))))) { ?>
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo language::strftime(language::$selected['format_date'], strtotime('-1 day', strtotime(reference::product($product_id)->date_valid_to))); ?></strong>

        <?php } else if (!empty(reference::product($product_id)->backorder) || (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s')))) ){ ?>   
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo language::strftime(language::$selected['format_date'], strtotime('-1 day', strtotime(reference::product($product_id)->date_valid_from))); ?></strong>

        <?php } else if (!empty(reference::product($product_id)->backorder) || (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s')))) ){ ?>   
         <strong><?php echo language::translate('title_closing_date', 'Closing Date:'); ?> <?php echo language::strftime(language::$selected['format_date'], strtotime('-1 day', strtotime(reference::product($product_id)->date_valid_from))); ?></strong>
        </br>
        <strong><?php echo language::translate('title_unfortunately_preorder', 'Unfortunately Pre-order'); ?> </strong> 

        <?php } else if (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder))) { ?>
         <strong><?php echo language::translate('title_unfortunately_preorder', 'Unfortunately Pre-order'); ?> </strong>  
        <?php } ?>
        <?php } ?>
		</div> 

     <?php if (empty(reference::product($product_id)->box_conditions)) { ?>	

        <?php  if ((!empty(reference::product($product_id)->addtocart) || (!empty(reference::product($product_id)->preorderable)))) { ?>
        <span style=" font-size: 18px;"><strong><?php echo language::translate('title_conditions', 'Condition:'); ?></strong></span></br>

        <?php } else if (!empty(reference::product($product_id)->preowned)) { ?>
        <span style=" font-size: 18px;"><strong><?php echo language::translate('title_box_damaged', 'Box Damaged !!'); ?></strong></span></br>
        <?php } ?>
        <?php } ?>
        <span style=" font-size: 18px;"><strong><?php echo ucwords(nl2br($box_conditions)); ?></strong></span>

      	
      <hr />

        
         <a class="box-wishlist text-center"><button class="wish"><strong>Wishlist !! </strong><i class="fa fa-heart" aria-hidden="true"></i><i class="fa fa-spinner fa-spin" aria-hidden="true"> </i></button></a> 
          &nbsp;&nbsp;&nbsp;
         <a href="https://t.me/ittoys"><span style="color: black; font-size:18px;"><strong>Telegram Channel <span style="color: #001ede; font-size:20px;"><i class="fa fa-telegram" aria-hidden="true"></i></span></span></strong></a>
       
      <div class="buy_now" style="margin: 1em 0;">
        <?php echo functions::form_draw_form_begin('buy_now_form', 'post'); ?>
        <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>

        <?php if ($options) { ?>
<?php if ($images_for_options) { ?><script>
      var images_for_options_a = [];
      var images_for_options_t = [];
      var images_for_options_t2 = [];
      <?php foreach ($images_for_options as $_i => $image) {
        if($_i === 0) { echo 'var images_for_options_first_id = '.$image['id'].';'."\n"; } 
        echo 'images_for_options_a['.$image['id'].']="'.document::href_link(WS_DIR_HTTP_HOME . $image['original']).'"'."\n";
        echo 'images_for_options_t['.$image['id'].']="'.document::href_link(WS_DIR_HTTP_HOME . $image['thumbnail']).'"'."\n";
        echo 'images_for_options_t2['.$image['id'].']="'.document::href_link(WS_DIR_HTTP_HOME . $image['thumbnail_2x']).'"'."\n"; 
      } ?>
      var set_image_for_option = function(id){
        if(id === undefined || id < 1) { id = images_for_options_first_id; }
        var a = $('#box-product .main-image');
        var img = $('#box-product .main-image > img');
        a.attr('href', images_for_options_a[id]);
        img.attr('src', images_for_options_t[id]);
        img.attr('srcset', images_for_options_t[id]+" 1x, "+images_for_options_t2[id]+" 2x");        
      };
      
      $("select[name^='options\[']").change(function(e){
        var id = $(this).find('option:selected').data('image-id');
        set_image_for_option(id);         
      });

      $("input[type='radio'][name^='options\[']").change(function(e){
        var id = $(this).data('image-id');        
        set_image_for_option(id);         
      });      
      </script><?php } ?>
          <?php foreach ($options as $option) { ?>
          <div class="form-group">
            <label><?php echo $option['name']; ?></label>
            <?php echo $option['description'] ? '<div>' . $option['description'] . '</div>' : ''; ?>
            <?php echo $option['values']; ?>
          </div>
          <?php } ?>
        <?php } ?>



       <?php if ($cheapest_shipping_fee !== null) { ?>
      <div class="cheapest-shipping" style="margin: 1em 0;">
        <?php echo functions::draw_fonticon('fa-truck'); ?> <?php echo strtr(language::translate('text_cheapest_shipping_from_price', 'Cheapest shipping from <strong class="value">%price</strong>'), array('%price' => currency::format($cheapest_shipping_fee))); ?><strong></strong>
      </div>
      <?php } ?>     

        <?php  if ((!empty(customer::$data['id'])  && (!empty(reference::product($product_id)->signin)) && (!empty(reference::product($product_id)->guest_price_prices))) 
        || (!empty(customer::$data['id']) || (empty(customer::$data['id'])  && (!empty(reference::product($product_id)->no_customer_group_prices))))
        || (empty(customer::$data['id']) && (!empty($campaign_price) && (empty(reference::product($product_id)->sign_in_deal)) && (!empty(reference::product($product_id)->sign_in))))
        || (empty(customer::$data['id']) && (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal)) || (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in)))))
        || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in)) && (empty(reference::product($product_id)->sign_in_deal)))
        || (empty(customer::$data['id']) && (empty(reference::product($product_id)->sign_in)) && (empty(reference::product($product_id)->signin)) && ((!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))
        || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->stock_quantity_guest_prices)))
        ) { ?>
        <!-- NONE --> 

        <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->stock_quantity_prices)))  { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span><span style="color: red;"><span style=" font-size: 26px;"> 

        <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices) ))) { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong>

        <?php } else if (!empty(customer::$data['id']) && (!empty($campaign_price) && (empty(reference::product($product_id)->sign_in_deal)) && (!empty(reference::product($product_id)->sign_in)) )) { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s>

        <?php } else if (!empty(customer::$data['id']) && (empty($campaign_price) && (empty(reference::product($product_id)->signin)) && (!empty(reference::product($product_id)->default_price_price)))) { ?>
        <!-- NONE -->

        <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['enable_wholesale_price']) && (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal)) || (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in)) )))) { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span><span style="color: black;"><span style=" font-size: 23px;"> 

        <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in)) && (empty(reference::product($product_id)->sign_in_deal)) ) { ?>        
        <span style=" font-size: 21px;"><s><?php echo currency::format (reference::product($product_id)->original_price); ?></s></span><span style="color: black;"><span style=" font-size: 26px;"> 

        <?php } else if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty($campaign_price) && (!empty(reference::product($product_id)->customer_group_prices)))) && (!empty(reference::product($product_id)->customer_group_prices))) { ?>
        <!-- NONE -->

        <?php if (!empty(reference::product($product_id)->customer_group_prices) && (empty(reference::product($product_id)->no_customer_group_prices))) { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span>
        <?php } ?>

        <?php } else if (!empty(customer::$data['id']) && (empty($campaign_price) && ((!empty(reference::product($product_id)->guest_price_prices)) || (!empty(reference::product($product_id)->customer_group_prices))))) { ?>
        <span style=" font-size: 21px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span>

        <?php } else if ((!empty(customer::$data['id']) && (empty($campaign_price) && ((empty(reference::product($product_id)->guest_price_prices)) || (empty(reference::product($product_id)->customer_group_prices))))) 
        || (empty(customer::$data['id']) && (empty($campaign_price) && ((empty(reference::product($product_id)->guest_price_prices)) || (empty(reference::product($product_id)->customer_group_prices)))))
        ) { ?>
        <!-- NONE -->   
        <?php } ?>
        </br>

<!--
        <?php if ((!empty(customer::$data['id']) && (!empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->guest_price_prices) && (empty($campaign_price))))) 
        || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_master_customer_special_price) && (!empty(reference::product($product_id)->customer_group_prices) && (empty($campaign_price)))))
        ) { ?>
        <span style=" font-size: 21px; color: black;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span>
        <?php } ?>
-->   
        
        
      
   <style>
   #box-product .quantity-prices {
    font-size: 18px;
    font-weight: bold;
    background-image: url("/images/transparent.png");
   }
   #box-product .quantity-prices {
     background-image: url("/images/transparent.png");
     border-collapse: separate;
     border-collapse: collapse;
     width: 25%;
   }
   #box-product .quantity-prices td, #box-product .quantity-prices th {
     padding: 3px 5px;
     cursor: pointer;  
     width: 35%;
   }
   </style>

</span>

    <?php if ((!empty($quantity_prices)) && (empty(customer::$data['code'])) && (empty(customer::$data['enable_quantity_price']) && (!empty(customer::$data['id'])) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s'))))   { ?> 
      <div class="price-wrapper">
        <table class="quantity-prices table table-striped data-table">
          <thead>
            <tr>
              <th><?php echo language::translate('title_qty', 'Qty'); ?></th>
              <th><?php echo language::translate('title_unit_price', 'Per /pcs'); ?></th>
            <tr>
          </thead>
          <tbody>
            <tr data-quantity="1" data-price="<?php echo $campaign_price ? $campaign_price : $regular_price; ?>" data-tax="<?php echo $campaign_price ? currency::format_raw(tax::get_tax($campaign_price, $tax_class_id)) : currency::format_raw(tax::get_tax($regular_price, $tax_class_id)); ?>" data-tax-formatted="<?php echo $campaign_price ? currency::format(tax::get_tax($campaign_price, $tax_class_id)) : currency::format(tax::get_tax($regular_price, $tax_class_id)); ?>">
              <td>1 pcs </td>
              <td>
                : <?php if ($campaign_price) { ?>
                <del class="regular-price"><?php echo $regular_price; ?></del> <strong class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>
                <?php } else { ?>
                <span class="price"><?php echo currency::format($regular_price); ?></span>
                <?php } ?>
              </td>
            <tr>
            <?php foreach ($quantity_prices as $quantity_price) { ?>
            <tr data-quantity="<?php echo $quantity_price['quantity']; ?>" data-price="<?php echo currency::format_raw($quantity_price['price']); ?>" data-tax="<?php echo currency::format_raw(tax::get_tax($quantity_price['price'], $tax_class_id)); ?>" data-tax-formatted="<?php echo currency::format(tax::get_tax($quantity_price['price'], $tax_class_id)); ?>">
              <td><?php echo $quantity_price['quantity']; ?> pcs </td>
              <td>: <?php echo currency::format($quantity_price['price']); ?></td>
            <tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

<script>
  $(document).ready(function(){
  $('#box-product .price-wrapper tbody tr').click(function(){
    $('input[name="quantity"]').val($(this).data('quantity')).trigger('change');
  });

  $('#box-product').on('keyup change', 'input[name="quantity"]', function(){
    var quantity = $(this).val();
    $('#box-product .price-wrapper tbody tr').css('background', '#ffffff00').css('color', 'inherit');
    $($('#box-product .price-wrapper tbody tr').get().reverse()).each(function(i, row){
    console.log($(row).data('quantity'), quantity);
      if ($(row).data('quantity') <= quantity) {
        $(row).css('background', '#ffffff00').css('color', '#FF0000');
        $('#box-product .tax .amount-formatted').text($(row).data('tax-formatted') + " ("+ Math.round($(row).data('tax')/$(row).data('price')*100) +" %)");
        return false;
      }
    });
  });

  $('input[name="quantity"]').trigger('change');
  });
</script>




      <?php } else { ?>

<table auto="" border="0" cellpadding="5" cellspacing="3" style="background-color: transparent; color: #5f6062;  text-align: left;"><tbody>
<tr align="left"><td width="200"><div class="separator" style="clear: both; text-align: center;">
</div>

            <div class="price-wrapper2">
            <?php if ((empty(customer::$data['id']) && (!empty(reference::product($product_id)->signin) && (empty(reference::product($product_id)->disable_sign_in)))) 
            || (empty(customer::$data['id']) &&  (!empty(reference::product($product_id)->sign_in_date_price_prices)))            
            || (empty(customer::$data['id']) && (!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal)))
            || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices) && (!empty(reference::product($product_id)->addtocart) && (!empty(reference::product($product_id)->newarrival))))
            || (empty(customer::$data['id']) && ((!empty(reference::product($product_id)->guest_price_prices) && (!empty(reference::product($product_id)->sign_in)))))
            )) { ?> 
            <span style="font-size: medium;">
            <a href="<?php echo document::href_link('login', array('redirect_url' => document::link())); ?>"><?php echo language::translate('text_Kindly_sign_in_to_see_price_&_picture_/_order_this_item', 'Kindly sign in to see price & picture / order this item'); ?></span></a>
            </br>
            </br>
            

            
            <?php } else if (!empty(customer::$data['id']) && ($campaign_price)) { ?>
            </s></span><span style=" font-size: 21px;"><s><span style="color: black;"> <strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span>
           
            <?php if (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices))) { ?> 
            </br></span><span style=" font-size: 25px;"><span style="color: blue;"> <s><strong><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></s></span>
            <?php } ?>
            
            <?php if (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price)))) { ?> 
            </br></span><span style=" font-size: 27px;"><span style="color: green;"> <s><strong><?php echo currency::format (reference::product($product_id)->default_price_price); ?></strong></s></span>
            <?php } ?>
            </br></span> <strong class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>
            
            <?php } else if (!empty(customer::$data['id']== 4640) || (!empty(customer::$data['id']== 4785))) { ?>
            <strong><?php echo currency::format($regular_price); ?></strong></span></br>             
            <strong><?php echo currency::format (reference::product($product_id)->purchase_price); ?></strong></span> 
            
            <?php } else if (empty(customer::$data['international']) && (!empty(reference::product($product_id)->guess_price))) { ?>   
            <strong>$ <span class="price"><?php echo nl2br($guess_price); ?></strong>            
            
            <?php } else if (!empty(customer::$data['id']) && (!empty($campaign_price) )) { ?> 
            <s><strong><span style=" font-size: 22px;"><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></span></s></br>      
            
            <?php  if (!empty(reference::product($product_id)->guest_price_prices)) { ?> 
            <s></span><span style=" font-size: 27px;"><span style="color: blue;"><strong><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></s></br>
            <?php } ?>
            
            <?php  if (!empty(reference::product($product_id)->default_price_price) && (empty(customer::$data['enable_wholesale_price']))) { ?> 
            <s><strong></span><span style=" font-size: 30px;"><span style="color: green;"><?php echo currency::format (reference::product($product_id)->default_price_price); ?></strong></s></br>
            <?php } ?>  
            
            <strong class="campaign-price2"><span style=" font-size: 33px;"><span style="color: red;"><?php echo currency::format($campaign_price); ?></span></strong>
            <?php } else if (!empty(customer::$data['code']) && (!empty($campaign_price))) { if(!empty(reference::product($product_id)->sign_in_deal)) { ?>
            <strong class="campaign-price2"><span style=" font-size: 29px;"><span style="color: red;"><?php echo currency::format($campaign_price); ?></span></strong>
            <?php } else {?>

            <span style=" font-size: 25px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></br>
            <?php }} else { ?>

            <?php if (!empty(customer::$data['id']) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin)))) { ?> 
            <span style=" font-size: 25px;"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></br> 

            <?php  if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->guest_price_prices)))) { ?> 
            <span style=" font-size: 27px;"><s><strong></span><span style="color: green;"><?php echo currency::format (reference::product($product_id)->customer_group_prices); ?></strong></s></br> 
            <?php } ?>

            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->insaneprice)))) { ?>
            <span style=" font-size: 26px; color: black">  <strong><?php echo currency::format($regular_price); ?></strong></span> 
            
            <!-- 06 Sep 2021 -->
            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->disable_master_guest_special_price || master_guest_special_price)))) { ?>
            <span style=" font-size: 26px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br> 
            <span style=" color: red">  <strong><?php echo currency::format($regular_price); ?></strong></span>          
        
            <?php } else if (!empty(customer::$data['code'])) { ?>
            <span class="price" style="color: black;"><strong><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (!empty($campaign_price)) { ?>
            <span style=" font-size: 22px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br>

            <?php if (!empty(reference::product($product_id)->guest_price_prices)) { ?>
            <span style="color: blue;"><del class="regular-price2"><span style=" font-size: 24px;"><strong><?php echo currency::format($regular_price); ?></strong></del></br> 
            <?php } ?>

            <strong class="campaign-price2"><?php echo currency::format($campaign_price); ?></strong>

            <?php } else { ?>
            <span class="price"><?php  if ((!empty(customer::$data['id']) && (!empty(reference::product($product_id)->guest_price_prices))) ||  ((!empty(customer::$data['id'])) && (empty(reference::product($product_id)->no_customer_group_prices)) && (!empty(reference::product($product_id)->customer_group_prices)) ) ) { ?> 
            <span style="color: red;"><span style=" font-size: 29px;">
            
            <?php } else if (empty(customer::$data['id']) && ((!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))) { ?>
            <span style="color: red;"><span style=" font-size: 29px;">
            <?php } ?> 
            
            <?php if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->no_customer_group_prices))) { ?>
            <!-- NONE -->

            <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->customer_group_prices) && (empty(reference::product($product_id)->customer_specialprice) 
            && (empty(reference::product($product_id)->default_price_price) )) 
            || (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices))) 
            || (empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices))
            )))) { ?>
            <span style=" font-size: 23px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br> 
            
            <?php if (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->master_guest_special_price))
            || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->disable_master_guest_special_price)))
            ) { ?>
            <span style=" font-size: 27px; color: blue;"><s><strong><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></s></span></br>
            <?php } ?>
            
            <?php } else if (empty(customer::$data['id']) && ((!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->guest_price_prices))))) { ?>
            <?php if (empty(reference::product($product_id)->no_customer_group_prices)) { ?>
            <span style=" font-size: 23px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br>  
            <?php } ?>
        
            <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->guest_price_prices))) { ?>
            <span style=" font-size: 23px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br>
            <span style=" font-size: 26px; color: blue"><s><strong><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></s></span> </br> 

            <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_master_guest_special_price))) { ?>
            <span style=" font-size: 26px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br> <span style=" color: red">

            <?php } else if (!empty(customer::$data['id'])) { ?>
            <span style=" font-size: 23px; color: black"><s><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></s></span> </br>  
            <?php } ?> 
        
            <?php if (!empty(reference::product($product_id)->no_customer_group_prices)) { ?>
            <span style=" font-size: 29px; color: black">

            <?php } else if (empty(reference::product($product_id)->customer_group_prices)) { ?>
            <span style=" font-size: 29px; color: red">
            <?php } ?>   
            
            <?php if ((empty(customer::$data['id']) || (!empty(customer::$data['id']))) && (!empty(reference::product($product_id)->guest_price_prices)) && (empty(reference::product($product_id)->no_customer_group_prices))) { ?>
            <span style=" color: red">
            <?php } else if (empty(customer::$data['id']))  { ?>           
            
            <span style=" color: black">
            <?php } ?> 
            <strong><?php echo currency::format($regular_price); ?></strong></span> 

            <?php } ?>            
            <?php } ?>
       </div>     
      <?php } ?> 
      
      </span>    
</span>
</td>
<td>
    <?php if (!empty(reference::product($product_id)->shopee) && (reference::product($product_id)->quantity > 0)) { ?> 		
           <?php if (!empty(reference::product($product_id)->shopee)) { ?> 
           <a href="<?php echo $shopee ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Shopee Logo.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Shopee Logo.png" /> 
           <?php } ?>
</td>
<td>
           <?php if (!empty(reference::product($product_id)->lazada) && (reference::product($product_id)->quantity > 0)) { ?> 
           <a href="<?php echo $lazada ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Lazada Logo.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Lazada Logo.png" />  
           <?php } ?>

</td>
</tr>
<tr align="left"><td valign="top">

</td>
<td valign="top">
           <?php if (!empty(user::$data['status']) && (!empty(reference::product($product_id)->shopee_backend))) { ?> 
           <a href="<?php echo $shopee_backend ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Shopee Backend.jpg" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Shopee Backend.jpg" />           
           <?php } ?>
</td>
<td valign="top">
           <?php if (!empty(user::$data['status']) && (!empty(reference::product($product_id)->lazada_backend))) { ?>    
           <a href="<?php echo $lazada_backend ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Lazada Backend.jpg" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Lazada Backend.jpg" /> 
           <?php } ?>


</td></tr>
</tbody>
<?php } ?>
</table>
    
      



















        </br>
      <div style="margin:0 0 1em 0; color:red;">
		<?php
		  if ($max_qty) {
		    echo language::translate('warn_item_max_qty', 'The maximum purchase of this product is').' '.$max_qty;
		  }
		?>
      </div>

      	

      <div style="margin:0 0 1em 0; color:red;">
		<strong><?php
		  $display_qty = '1';
		  if ($min_qty) {
		    echo language::translate('warn_item_min_qty', 'The minimum purchase of this product is').' '.$min_qty;
			$display_qty = $min_qty;
		  }
		?></strong>
      </div>
    
      	
        <?php if (!$catalog_only_mode) { ?>
        <div class="form-group">
          <label><?php echo language::translate('title_quantity', 'Quantity'); ?></label>
          <div style="display: flex">
            <div class="input-group" style="flex: 0 1 150px;">
              
     <?php echo (!empty($quantity_unit['decimals'])) ? functions::form_draw_decimal_field('quantity', isset($_POST['quantity']) ? true : $display_qty, $quantity_unit['decimals'], 1, null) : (functions::form_draw_number_field('quantity', isset($_POST['quantity']) ? true : $display_qty, 1)); ?>
      	
              <?php echo !empty($quantity_unit['name']) ? '<div class="input-group-addon">'. $quantity_unit['name'] .'</div>' : ''; ?>
            </div>

            
	 <div style="flex: 1 0 auto; padding-left: 1em;">
	 	 
	 <?php if (!empty(customer::$data['id']) && 
	 (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in))
	 || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_sign_in)))
	 ) { ?> 
	 
			
	            <?php if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (!empty($campaign_price))
	            || (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) && (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
	            ) { ?>
	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button> 

	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>	
	            
	            <?php } else if ((!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))) 
				|| (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))))
				|| (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->preorderable))
				){ ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_preorder', 'Insane Pre-Order !!'); ?></i></b></button>

                <?php } else if (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->backorder)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Orange Red.jpg');" value="true" type="submit">
	            <span style="color: #b6ff9c;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_backorder', 'Insane Backorder'); ?></i></b></button>			

                <?php } else if  (!empty($campaign_price) && (!empty(reference::product($product_id)->preorderable) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s')) )) { ?> 
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>

	            <?php } else if (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) { ?>
	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button> 

                <?php } else if ((!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))) 
                || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
                ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	            
	            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))
	            || (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (($quantity > 0 || !empty($product->sold_out_status['orderable'])) && ((!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal))))))))
	            || (reference::product($product_id)->date_valid_from <=> date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->insanedeal)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>

            	<?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))))) { 
            	if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg'); opacity: 0.5;"  type="submit" disabled="disabled">
                <?php } else { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg');"  type="submit">
                <?php } ?> 
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_vip_order', 'V.I.P Order'); ?></i></b></button> 
	            
            	<?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->addtocart) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))))) { 
            	if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg'); opacity: 0.5;"  type="submit" disabled="disabled">
                <?php } else{ ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');"  type="submit">
                <?php } ?> 
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price'); ?></i></b></button>               
                
                
                
                
                
                

	            
                <?php } else if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices))
                || ($preorderable && $prebackorder && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))
                || ($addtocart && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))
                || (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
                || ($backorder && ($quantity <= 0 ))
                || (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity <= 0 || !empty($product->sold_out_status['orderable']))))))
                || ($restock && ($quantity <= 0 ))
                || ($preorderable && ($quantity <= 0 ))
                ) { ?> 
 	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>       

                <?php } else if (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>	 

                <?php } else if (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->pending_guest))))
                || (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (empty(customer::$data['id']) && (!empty(reference::product($product_id)->pending_guest))))
                ) { ?>
                <button class="btn btn-success btn-block_product" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
	            <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_pending', 'Pending'); ?></i></b></button> 

	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>
	            
           

                <?php } else if (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_pre_-_order', 'Pre-Order'); ?></i></b></button>

                <?php } else if (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_pre_-_order', 'Pre-Order'); ?></i></b></button>
	            
	            <?php } else if ((reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->prebackorder)
	            || (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Purple Blue.jpg');" value="true">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_pre_-_backorder', 'Pre-Backorder'); ?></i></b></button>
	            
	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>	
	            
                <?php } else if ($preorderable && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" class="btn btn-default btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
                <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button> 	            

                <?php } else if ($preowned && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Yellow.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_pre_-_owned', 'Pre-owned'); ?></i></b></button> 

	            <?php } else if ($backorder && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Orange.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_backorder', 'Backorder'); ?></i></b></button>

                <?php } else if (!empty($newarrival) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>

	            <?php } else if (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->restock))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pink.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_restock', 'Restock'); ?></i></b></button>

                <?php } else if (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))
                || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
                ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true" type="submit">
                <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
                <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button>

                <?php } else if ($pending && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
	            <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_pending', 'Pending'); ?></i></b></button>  
                
	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->backorder))
	            || (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>

                <?php } else if ($newarrival && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>

                <?php } else if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true">
	            <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button> 


                <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->customer_group_prices))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');"  type="submit">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>



	            
	            <?php } else if (!empty(customer::$data['id']) && (empty($campaign_price) && (!empty(reference::product($product_id)->customer_group_prices)))
	            || (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->addtocart)))
	            || (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->addtocart)))
	            || ($addtocart && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))
	            
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');"  type="submit">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>


                
                <?php } else if (!empty(reference::product($product_id)->specialprice) && ($specialoffer && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))
                || (!empty(reference::product($product_id)->specialprice) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->specialoffer))))
                ) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true">
	            <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button> 
	            
                <?php } else { ?>
                <?php echo '<button class="btn btn-success" name="add_cart_product" value="true" type="submit"'. (($quantity <= 0 && !$orderable) ? ' disabled="disabled"' : '') .'>'. language::translate('title_add_to_cart', 'Add To Cart') .'</button>'; ?>
             <?php } ?>
      

	 <?php } else if ((empty(customer::$data['id']) && ((!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal))) 
	 || (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in))) 
	 || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices)))
	 || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)))
	 ) { ?> 
	 
     <?php } else if (!empty(customer::$data['id']) 
      
     || (empty(reference::product($product_id)->signin) && (empty(reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s'))) )) { ?>
     
			
	            <?php if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (!empty($campaign_price))
	            || (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) && (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
	            ) { ?>
	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button> 

	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>	
	            
	            <?php } else if ((!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))) 
				|| (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))))
				|| (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->preorderable))
				){ ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_preorder', 'Insane Pre-Order !!'); ?></i></b></button>

                <?php } else if (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->backorder)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Orange Red.jpg');" value="true" type="submit">
	            <span style="color: #b6ff9c;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_backorder', 'Insane Backorder'); ?></i></b></button>			

                <?php } else if  (!empty($campaign_price) && (!empty(reference::product($product_id)->preorderable) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s')) )) { ?> 
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>

	            <?php } else if (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) { ?>
	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button> 

                <?php } else if ((!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))) 
                || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
                ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	            
	            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))
	            || (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (($quantity > 0 || !empty($product->sold_out_status['orderable'])) && ((!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal))))))))
	            || (reference::product($product_id)->date_valid_from <=> date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->insanedeal)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>

            	<?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))))) { 
            	if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg'); opacity: 0.5;"  type="submit" disabled="disabled">
                <?php } else { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg');"  type="submit">
                <?php } ?> 
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_vip_order', 'V.I.P Order'); ?></i></b></button> 
	            
            	<?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->addtocart) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))))) { 
            	if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg'); opacity: 0.5;"  type="submit" disabled="disabled">
                <?php } else{ ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');"  type="submit">
                <?php } ?> 
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price'); ?></i></b></button>               
                
                
                
                
                
                

	            
                <?php } else if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices))
                || ($preorderable && $prebackorder && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))
                || ($addtocart && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))
                || (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
                || ($backorder && ($quantity <= 0 ))
                || (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity <= 0 || !empty($product->sold_out_status['orderable']))))))
                || ($restock && ($quantity <= 0 ))
                || ($preorderable && ($quantity <= 0 ))
                ) { ?> 
 	            <button class="sold_out_2" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>       

                <?php } else if (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	            <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>	 

                <?php } else if (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->pending_guest))))
                || (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (empty(customer::$data['id']) && (!empty(reference::product($product_id)->pending_guest))))
                ) { ?>
                <button class="btn btn-success btn-block_product" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
	            <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_pending', 'Pending'); ?></i></b></button> 

	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>
	            
           

                <?php } else if (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_pre_-_order', 'Pre-Order'); ?></i></b></button>

                <?php } else if (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_pre_-_order', 'Pre-Order'); ?></i></b></button>
	            
	            <?php } else if ((reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->prebackorder)
	            || (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Purple Blue.jpg');" value="true">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_pre_-_backorder', 'Pre-Backorder'); ?></i></b></button>
	            
	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->prebackorder))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>	
	            
                <?php } else if ($preorderable && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" class="btn btn-default btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
                <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button> 	            

                <?php } else if ($preowned && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Yellow.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_pre_-_owned', 'Pre-owned'); ?></i></b></button> 

	            <?php } else if ($backorder && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Orange.jpg');" value="true">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_backorder', 'Backorder'); ?></i></b></button>

                <?php } else if (!empty($newarrival) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>

	            <?php } else if (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->restock))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Pink.jpg');"  type="submit">
	            <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_restock', 'Restock'); ?></i></b></button>

                <?php } else if (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))
                || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
                ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true" type="submit">
                <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
                <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button>

                <?php } else if ($pending && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
	            <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i> <?php echo language::translate('title_pending', 'Pending'); ?></i></b></button>  
                
	            <?php } else if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->backorder))
	            || (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->preorderable)))
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" disabled="disabled" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>

                <?php } else if ($newarrival && ($quantity > 0 || !empty($product->sold_out_status['orderable']))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	            <span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>

                <?php } else if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true">
	            <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button> 


                <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->customer_group_prices))) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');"  type="submit">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>



	            
	            <?php } else if (!empty(customer::$data['id']) && (empty($campaign_price) && (!empty(reference::product($product_id)->customer_group_prices)))
	            || (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->addtocart)))
	            || (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->addtocart)))
	            || ($addtocart && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))
	            
	            ) { ?>
                <button class="btn btn-success btn-block_product" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');"  type="submit">
	            <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	            <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>


                
                <?php } else if (!empty(reference::product($product_id)->specialprice) && ($specialoffer && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))
                || (!empty(reference::product($product_id)->specialprice) && (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->specialoffer))))
                ) { ?>
                <button class="btn btn-success btn-block_product" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true">
	            <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	            <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button> 
	            
                <?php } else { ?>
                <?php echo '<button class="btn btn-success" name="add_cart_product" value="true" type="submit"'. (($quantity <= 0 && !$orderable) ? ' disabled="disabled"' : '') .'>'. language::translate('title_add_to_cart', 'Add To Cart') .'</button>'; ?>
             <?php } ?>
      
                
     <?php } ?>
      

            </div>
          </div>
        </div>
        <?php } ?>

        <?php echo functions::form_draw_form_end(); ?>

			
        <?php } else { ?>
          <div style="text-align: center;">
          
          <p><?php echo language::translate('text_enable_hide_product', 'Product Not Found'); ?></a></p
        <?php } ?>
       
      

			
        <?php } else { ?>
          <div style="text-align: center;">
          
          <p><a href="<?php echo document::href_link('login', array('redirect_url' => document::link())); ?>"><?php echo language::translate('text_Kindly_sign_in_to_see_image_/_price_/_order_this_item', 'Kindly sign in to see image / price / order this item'); ?></a></p
        <?php } ?>
       
      

			
        <?php } else { ?>
          <div style="text-align: center;">
          <p><a href="<?php echo document::href_link('login', array('redirect_url' => document::link())); ?>"><?php echo language::translate('text_Kindly_sign_in_to_see_image_/_price_/_order_this_item', 'Kindly sign in to see image / price / order this item'); ?></a></p
        <?php } ?>
       
      
      </div>

      <hr />


      <span style=" font-size: 14px;"><?php echo language::translate('title_your_entire_order', 'Your entire order'); ?>
      </div>
      	
      <div class="social-bookmarks text-center">
        <a class="link" href="#"><?php echo functions::draw_fonticon('fa-link', 'style="color: #333;"'); ?></a>
        <a class="twitter" href="<?php echo document::href_link('http://twitter.com/home/', array('status' => $name .' - '. $link)); ?>" target="_blank" title="<?php echo sprintf(language::translate('text_share_on_s', 'Share on %s'), 'Twitter'); ?>"><?php echo functions::draw_fonticon('fa-twitter-square fa-lg', 'style="color: #55acee;"'); ?></a>
        <a class="facebook" href="<?php echo document::href_link('http://www.facebook.com/sharer.php', array('u' => $link)); ?>" target="_blank" title="<?php echo sprintf(language::translate('text_share_on_s', 'Share on %s'), 'Facebook'); ?>"><?php echo functions::draw_fonticon('fa-facebook-square fa-lg', 'style="color: #3b5998;"'); ?></a>
        <a class="googleplus" href="<?php echo document::href_link('https://plus.google.com/share', array('url' => $link)); ?>" target="_blank" title="<?php echo sprintf(language::translate('text_share_on_s', 'Share on %s'), 'Google+'); ?>"><?php echo functions::draw_fonticon('fa-google-plus-square fa-lg', 'style="color: #dd4b39;"'); ?></a>
        <a class="pinterest" href="<?php echo document::href_link('http://pinterest.com/pin/create/button/', array('url' => $link)); ?>" target="_blank" title="<?php echo sprintf(language::translate('text_share_on_s', 'Share on %s'), 'Pinterest'); ?>"><?php echo functions::draw_fonticon('fa-pinterest-square fa-lg', 'style="color: #bd081c;"'); ?></a>
      </div>


      <?php error_reporting(0); ?>
        <?PHP if(isset($customer_group_prices)) { ?>
        
        <span style="color:red; margin-bottom: 10px; display:block;"> Click <i class="fa fa-eye" id="customer_group_price_show_hide" style="color:blue;" aria-hidden="true"></i> to see customer group prices</span>
        <div style="overflow-x: scroll; display:none;" id="customer-group-prices">
        <table class="table table-striped data-table">
          <tbody>
          
          <?php if (!empty($customer_group_prices)) foreach (array_keys($customer_group_prices) as $key) { ?>
            <tr>

              <td><?php
              $customer_groups_query = database::query(
              "select * from ". DB_TABLE_CUSTOMER_GROUPS ."
               order by name asc;"
              );

              while($customer_group = database::fetch($customer_groups_query)) {
                 if($customer_group['id'] === $customer_group_prices[$key]['customer_group_id'])
                 echo $customer_group['name'].' ';
              }
               ?></td>

              <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
              <td><?php echo $currency_code.":".$customer_group_prices[$key][$currency_code];?></td>
              <td><<?php echo currency::format (reference::product($product_id)->purchase_price); ?></td>

              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div>
        </div>
        </div>
        <script>
        $('body').on('click', '#customer_group_price_show_hide', function(){

              if($('#customer-group-prices').css('display') == "none"){
              console.log('tete');
              $('#customer-group-prices').css('display','block');
              }
              else{
              $('#customer-group-prices').css('display','none');
              }
        });
        </script>
        <?PHP }?>

      
    </div>
  </div>

  <?php if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { ?>
  <ul class="nav nav-tabs">
    <?php if ($description) { ?><li><a data-toggle="tab" href="#tab-description"><?php echo language::translate('title_description', 'Description'); ?></a></li><?php } ?>
    <?php if ($technical_data) { ?><li><a data-toggle="tab" href="#tab-technical-data"><?php echo language::translate('title_technical_data', 'Technical Data'); ?></a></li><?php } ?>
  </ul>

  <div class="tab-content">
    <div id="tab-description" class="tab-pane description">
      <?php echo $description; ?>
    </div>

    <?php if ($technical_data) { ?>
    <div id="tab-technical-data" class="tab-pane technical-data">
      <table class="table table-striped table-hover">
<?php
  for ($i=0; $i<count($technical_data); $i++) {
    if (strpos($technical_data[$i], ':') !== false) {
      @list($key, $value) = explode(':', $technical_data[$i]);
      echo '  <tr>' . PHP_EOL
         . '    <td>'. trim($key) .':</td>' . PHP_EOL
         . '    <td>'. trim($value) .'</td>' . PHP_EOL
         . '  </tr>' . PHP_EOL;
    } else if (trim($technical_data[$i]) != '') {
      echo '  <thead>' . PHP_EOL
         . '    <tr>' . PHP_EOL
         . '      <th colspan="2">'. $technical_data[$i] .'</th>' . PHP_EOL
         . '    </tr>' . PHP_EOL
         . '  </thead>' . PHP_EOL
         . '  <tbody>' . PHP_EOL;
    } else {
      echo ' </tbody>' . PHP_EOL
         . '</table>' . PHP_EOL
         . '<table class="table table-striped table-hover">' . PHP_EOL;
    }
  }
?>
      </table>
    </div>
    <?php } ?>
  </div>
  <?php } ?>

</article>

<script>
  Number.prototype.toMoney = function() {
    var n = this,
      c = <?php echo (int)currency::$selected['decimals']; ?>,
      d = '<?php echo language::$selected['decimal_point']; ?>',
      t = '<?php echo language::$selected['thousands_sep']; ?>',
      p = '<?php echo currency::$selected['prefix']; ?>',
      x = '<?php echo currency::$selected['suffix']; ?>',
      s = n < 0 ? '-' : '',
      i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + '',
      j = (j = i.length) > 3 ? j % 3 : 0;

    return s + p + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + x;
  }

  $('#box-product form[name=buy_now_form]').bind('input propertyChange', function(e) {

    var regular_price = <?php echo currency::format_raw($regular_price); ?>;
    var sales_price = <?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>;
    var tax = <?php echo currency::format_raw($total_tax); ?>;

    $(this).find('input[type="radio"]:checked, input[type="checkbox"]:checked').each(function(){
      if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
      if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
      if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
    });

    $(this).find('select option:checked').each(function(){
      if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
      if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
      if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
    });

    $(this).find('input[type!="radio"][type!="checkbox"]').each(function(){
      if ($(this).val() != '') {
      if ($(this).data('price-adjust')) regular_price += $(this).data('price-adjust');
      if ($(this).data('price-adjust')) sales_price += $(this).data('price-adjust');
      if ($(this).data('tax-adjust')) tax += $(this).data('tax-adjust');
      }
    });

    $(this).find('.regular-price').text(regular_price.toMoney());
    $(this).find('.campaign-price').text(sales_price.toMoney());
    $(this).find('.price').text(sales_price.toMoney());
    $(this).find('.total-tax').text(tax.toMoney());
  });

  $('#box-product[data-id="<?php echo $product_id; ?>"] .social-bookmarks .link').off().click(function(e){
    e.preventDefault();
    prompt("<?php echo language::translate('text_link_to_this_product', 'Link to this product'); ?>", '<?php echo $link; ?>');
  });
</script>
