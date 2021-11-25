
      <?php if (empty(customer::$data['enable_hide_product']) || (empty(reference::product($product_id)->hide_product))) { ?>
      

      <?php if (!empty(customer::$data['id']) || (empty(reference::product($product_id)->hide_product))) { ?>
      
<article class="product-column">
  
      <a class="link" href="<?php echo htmlspecialchars($link) ?>" title="<?php echo htmlspecialchars($name); ?>" data-id="<?php echo $product_id; ?>" data-sku="<?php echo htmlspecialchars($sku); ?>" data-name="<?php echo htmlspecialchars($name); ?>" data-price="<?php echo currency::format_raw($campaign_price ? $campaign_price : $regular_price); ?>" target="_blank">
      

    
      <?php if (empty(customer::$data['forbidden'] == 0) || (empty(reference::product($product_id)->forbidden))) { ?>
        <div class="image-wrapper">
          <img class="image img-responsive" src="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?>" srcset="<?php echo document::href_link(WS_DIR_APP . $image['thumbnail']); ?> 1x, <?php echo document::href_link(WS_DIR_APP . $image['thumbnail_2x']); ?> 2x" alt="<?php echo htmlspecialchars($name); ?>" />         
        </div> 
        
        <?php } else { ?>
        <?php if (empty(customer::$data['forbidden'] == 1) || (!empty(customer::$data['id'])) || (empty(reference::product($product_id)->forbidden))) { ?>
        <div class="image-wrapper">
          <img class="image img-responsive" style="max-width: 320px; max-height: 320px;" src="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>logotype hidden.png" />
        </div>
        <?php } ?>
        <?php } ?>
      
      




    <div class="info">
      
    <?php error_reporting(0); ?>
    <b><div class="name"><div class="form-group col-md-6"> <?php echo nl2br ($short_description) ; 
    if (user::$data['status']) {
    echo ' <a title="Edit Product" target="_blank" href="' . document::link(WS_DIR_ADMIN, array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => $product_id)) .'"> <br/><span style="color: blue;"><i class="fa fa-cog">&nbsp;</i></a>';
    }
   
    if (user::$data['status']) {
    echo ' <a title="Clone Product" target="_blank" href="' . document::link(WS_DIR_ADMIN, array('app' => 'catalog', 'doc' => 'edit_product', 'clone_id' => $product_id)) .'"><span style="color: green;"><i class="fa fa-copy"></i></a>'; 
    }    

    ?></b>
    <?php if (!empty(user::$data['status'])) { ?>

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
    
    <?php if (!empty(reference::product($product_id)->disable_sign_in)) { ?>
    &nbsp;<span style="color: red;"><i class="fa fa-image"></span></i>     
    <?php } ?>    
    
    <?php if (!empty(reference::product($product_id)->free_shipping)) { ?>
    &nbsp;<span style="color: #69004e;"><i class="fa fa-truck"></span></i>
    <?php } ?>   
    
    <?php if (!empty(reference::product($product_id)->no_free_shipping)) { ?>
    &nbsp;<span style="color: #ff0059;"><i class="fa fa-truck"></span></i>
    <?php } ?>      
    
    <?php } ?> 
    </div>
    </div>
            
      
<!--      <?php if (!empty($manufacturer)) { ?>
      <div class="manufacturer-name">
        <a href="<?php echo document::ilink('manufacturer', array('manufacturer_id' =>$manufacturer['id'])); ?>">
          <?php echo $manufacturer['name']; ?>
        </a>
      </div>
      <?php } ?>
       
      <?php if (!empty($listing_link)) { ?>
      <div class="listing_link-name">
        <a href="<?php echo document::ilink('listing_link', array('listing_link_id' =>$listing_link['id'])); ?>">
          <?php echo $listing_link['name']; ?>
        </a>
      </div>
      <?php } ?>
-->       
        <div class="manufacturer-name"><?php echo !empty($listing_info) ? nl2br($listing_info) : '&nbsp;'; ?></div>
      
      

    <?php if (!empty(user::$data['status'])) { ?>

    <?php if (!empty(reference::product($product_id)->specialprice)) { ?>
    &nbsp;<span style="color: #ffee00;"><i class="fa fa-star"></span></i>
    <?php } ?>   
    
    <?php if (!empty(reference::product($product_id)->customer_specialprice)) { ?>
    &nbsp;<span style="color: #23d400;"><i class="fa fa-star"></span></i>
    <?php } ?>  
    
    <?php if (!empty(reference::product($product_id)->customer_insaneprice)) { ?>
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
    </br>
    
    <span style="color: black;"><?php echo number_format("$opening_quantity"); ?> pcs &nbsp;&nbsp;&nbsp;<?php echo number_format("$quantity"); ?> pcs </br>
    
        <?php echo strtr("%length x %width x %height %class", array(
          '%length' => (float)reference::product($product_id)->dim_x,
          '%width' => (float)reference::product($product_id)->dim_y,
          '%height' => (float)reference::product($product_id)->dim_z,
          '%class' => reference::product($product_id)->dim_class,
        ));
      ?>
    </br>
    
    <?php if (!empty(user::$data['status'])) { ?>
    <?php echo weight::format(reference::product($product_id)->weight, reference::product($product_id)->weight_class, ); ?></span></br>
    <?php if ($sku) { ?>
    <?php echo $sku; ?></br>
    <?php echo $mpn; ?>
    <?php } ?> 
    <?php if (empty($mpn)) { ?>
    </br>
    <?php } ?> 
    <?php } ?> 
     <?php } ?> 
            

      <?php if (!empty(customer::$data['id']) || (empty(reference::product($product_id)->hidden))) { ?>
      
      

			<?php if (!empty(customer::$data['id']) || (empty(reference::product($product_id)->signin)) || (!empty(reference::product($product_id)->signin)) 
			|| (empty(reference::product($product_id)->addtocart)) || (!empty(reference::product($product_id)->addtocart))
			|| (empty(reference::product($product_id)->preorderable)) || (!empty(reference::product($product_id)->preorderable))
			|| (!empty(reference::product($product_id)->prebackorder))
			){ ?>
			
            <div class="price-wrapper">
            <?php if (empty(customer::$data['id'])  && (!empty(reference::product($product_id)->signin) && (empty(reference::product($product_id)->disable_sign_in)) 
            || (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in)) 
            || (!empty(reference::product($product_id)->sign_in_date_price_prices))
            || (!empty(reference::product($product_id)->sign_in_date_price_prices) && (!empty(reference::product($product_id)->addtocart) && (!empty(reference::product($product_id)->newarrival)))
            || (!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty(reference::product($product_id)->sign_in_deal))           
            || (!empty(reference::product($product_id)->insaneprice) && (!empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->sign_in_deal))
            || ((!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal)))             
            )))))) { ?> 
            </br>
            <span class="price"><strong class="regular-price"><strong><a href="<?php echo document::href_link('login', array('redirect_url' => document::link())); ?>">
            <?php echo "See Price"; ?></strong></strong></span></span>
            


            <?php } else if (!empty(customer::$data['id']== 4640)) { ?>            
            </br><strong><span class="price"><?php echo currency::format (reference::product($product_id)->purchase_price); ?></strong>  
            
            <?php } else if (!empty(customer::$data['id']== 4785)) { ?>
            <span class="price"><?php echo currency::format($regular_price); ?></span>
            


            <?php } else if (empty(customer::$data['international']) && (!empty(reference::product($product_id)->guess_price))) { ?>  
            </br><strong><span class="price">  $ <?php echo nl2br($guess_price); ?></strong>            

            <?php } else if ((!empty(customer::$data['id']) || (empty(customer::$data['id'])))  && (!empty(reference::product($product_id)->no_customer_group_prices))) { ?>
            </br><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></span>
            
            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)))) { ?> 
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->fake_sold_out_date_price_prices); ?></strong></span>            
          
            <?php } else if ((!empty($quantity_price)) && (!empty(customer::$data['id'])) && (empty(customer::$data['enable_quantity_price'])) && (reference::product($product_id)->date_valid_to >= date('Y-m-d H:i:s'))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s></br><span class="regular-price"><strong><span style=" font-size: 13.8px;"><?php echo currency::format($quantity_price); ?> - <?php echo currency::format($regular_price); ?></strong> </span>              

            <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->stock_quantity_guest_prices)))  { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->stock_quantity_prices)))  { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (empty(customer::$data['code']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal)) && (!empty(customer::$data['code'])) ))) { ?> 
            <s class="regular-price"><?php echo currency::format($regular_price); ?></s> <strong class="campaign-price"></br></br><?php echo currency::format($campaign_price); ?></strong> 

            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && ((!empty($campaign_price) && (!empty(reference::product($product_id)->sign_in_deal)) || (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices)) )) )) ) { ?>
            </br><strong><span class="price"><?php echo currency::format (reference::product($product_id)->original_price); ?></strong>

            <?php } else if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty($campaign_price) && (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->customer_specialprice) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->default_price_price) ))))))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->default_price_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty($campaign_price) && (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->customer_group_prices) && (!empty(reference::product($product_id)->guest_price_prices)  )))))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else { ?>

            <?php if ((!empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin))))) 
            || (!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin)))))
            || (!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin))))
            || (!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin)))))            
            ) { ?> 
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong class="campaign-price"></br><?php echo currency::format($campaign_price); ?></strong>

            <?php } else if (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal)) )) {?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s></br><span class="price"><span style="color: red;"><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></br></span>
          
            <?php } else if (((!empty($quantity_price)) && (!empty(customer::$data['id'])) && (empty(customer::$data['enable_quantity_price'])) && (reference::product($product_id)->date_valid_to >= date('Y-m-d H:i:s'))) 
            || (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->master_guest_special_price || disable_master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))
            || (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->disable_master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s></br><strong><span style="color: red;"><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else if (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices))))) { ?>
            </br><strong><span class="price"><?php echo currency::format (reference::product($product_id)->original_price); ?></strong>
          
            <?php } else if (!empty(customer::$data['code'])) { ?>
            </br><strong><span class="price"><?php echo currency::format (reference::product($product_id)->original_price); ?></strong>
          
            <?php } else if (!empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && ($campaign_price))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s></br> <strong class="campaign-price"><?php echo currency::format($campaign_price); ?></strong>

            <?php } else if ((empty(customer::$data['id']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))             
            || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))
            || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_master_guest_special_price)) && (!empty(reference::product($product_id)->guest_price_prices)))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else if ((!empty(customer::$data['id']) && (empty(reference::product($product_id)->disable_master_customer_special_price) && (empty(reference::product($product_id)->disable_master_guest_special_price)))) 
            || (!empty(customer::$data['id']) && (empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin))))))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (empty(customer::$data['id']) && (empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price) && (empty(reference::product($product_id)->signin)) )))) { ?> 
            </br><strong><?php echo currency::format (reference::product($product_id)->original_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['enable_wholesale_price']) && (!empty(customer::$data['disable_default_price']) ))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) )))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else if (((!empty(customer::$data['id']) &&  (!empty(reference::product($product_id)->master_wholesale_special_price) &&  (!empty(reference::product($product_id)->wholesale_price_price) && (!empty(reference::product($product_id)->wholesale_specialprice) && (!empty(customer::$data['enable_wholesale_price']) )))) && (!empty(customer::$data['disable_default_price'])))) 
            || ((!empty(customer::$data['id']) &&  (!empty(reference::product($product_id)->disable_master_wholesale_special_price) &&  (!empty(reference::product($product_id)->wholesale_price_price) && (!empty(reference::product($product_id)->wholesale_specialprice) && (!empty(customer::$data['enable_wholesale_price']) )))) && (!empty(customer::$data['disable_default_price']))))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if ((!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->customer_specialprice))))) && (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price) )))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->default_price_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (empty(reference::product($product_id)->customer_group_prices) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) )))) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else if ((!empty(customer::$data['id']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices))))) 
            || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices)))))
            || (!empty(customer::$data['id']) && (empty(reference::product($product_id)->specialprice) && (empty(reference::product($product_id)->guest_price_prices))))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>
        
            <?php } else if (((!empty(customer::$data['id']) && (empty(reference::product($product_id)->customer_specialprice))) && (!empty(reference::product($product_id)->default_price_price))) 
            || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)))))
            ) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></strong></span>

            <?php } else if ((empty(customer::$data['id']) && ($campaign_price)) 
            || (empty(customer::$data['id']) || (!empty(customer::$data['code']) && ($campaign_price)))
            ) { ?>
            </br><span class="price"><?php echo currency::format($regular_price); ?></span>
         
	        <?php } else if (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) )) { ?>
	        <s class="regular-price"><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

	        <?php } else if (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->default_price_price)) )) { ?>
	        <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;"></br><?php echo currency::format($regular_price); ?></strong></span>

            <?php } else if (!empty(customer::$data['id']) && (empty($campaign_price) && (!empty(reference::product($product_id)->customer_group_prices)))) { ?>

            <?php if (empty(reference::product($product_id)->no_customer_group_prices)) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s> <strong><span style="color: red;">

            <?php } ?> 
            </br>

            <?php if (!empty(reference::product($product_id)->no_customer_group_prices)) { ?>
            <strong>
            <?php } ?> 
            <?php echo currency::format($regular_price); ?></strong></span>
            <?php } else { ?>
         
            <?php if (!empty(reference::product($product_id)->guest_price_prices)) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->guest_price_prices); ?></s>
          
            <?php } else if (!empty(reference::product($product_id)->default_price_price)) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s>

            <?php } else if (empty(reference::product($product_id)->customer_group_prices)) { ?>
            <s class="regular-price"><?php echo currency::format (reference::product($product_id)->original_price); ?></s>
            <?php } ?> 

            </br><span class="price"><?php echo currency::format($regular_price); ?></span>

            <?php } ?>            
            <?php } ?>
            </div>
            <?php } ?>             

		<?php if (!empty(reference::product($product_id)->preorderable) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s'))) { ?>
		<span style=" font-size: 11px;"> <?php echo language::translate('title_closing_dates', 'Closing :'); ?> <?php echo language::strftime(language::$selected['format_date'], strtotime('-1 day', strtotime(reference::product($product_id)->date_valid_from))); ?></span></br>
        <?php } else if (!empty(reference::product($product_id)->preorderable) && (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s'))) { ?>
        </br>
        <?php } else if (empty(reference::product($product_id)->preorderable)) { ?>
        </br>
        <?php } ?>

		<?php if (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>	
		<span style=" font-size: 11px;">
		<?php if (!empty(reference::product($product_id)->preorderable)) { ?>
		<?php echo language::translate('title_eta', 'ETA :'); ?> 
		<?php } ?>
		<?php $array = reference::product($product_id)->delivery_status; print_r($array["name"]);?></span>
		
		<?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
		</br>
		<?php } ?>


      






    </div>

    
	<?php if ((reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') 
	&& (!empty(reference::product($product_id)->newarrival) 
	&& (empty(reference::product($product_id)->addtocart) 
	&& (empty(reference::product($product_id)->specialoffer) 
	&& (empty(reference::product($product_id)->backorder))))))) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	   <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
       
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	   <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	   <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button> 
	   
       <?php } else if ((!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) 
       || (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
       ) { ?>
       
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	   <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	   <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>	   

       <?php } else { ?>
       <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
       <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
       <
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true">
	   <b><i><span style="color: #7a0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	   <?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
       <?php echo functions::form_draw_form_end('buy_now_form'); ?>
       <?php } ?>
    </div>
    <?php } ?>
      

    
	<?php if (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && (empty($campaign_price))) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if ((empty(customer::$data['enable_wholesale_price']) && (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])) 
	  || (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable']))))
	  ) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>

      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" type="submit" name="add_cart_product" style="display: none;" value="true">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      <?php } ?>
    </div>
    <?php } ?>
      

    
	<?php if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && reference::product($product_id)->pending) { ?>
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>  
	  
      <?php } else if ((empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices))) 
      || (!empty(reference::product($product_id)->pending) && (!empty(reference::product($product_id)->no_customer_group_prices)))
      ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true" type="submit">
	  <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_pending', 'Pending'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>	
      
      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
        <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
         
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
		<span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
		<b><i><?php echo language::translate('title_pending', 'Pending'); ?></i></b></button>
        <?php echo functions::form_draw_form_end('buy_now_form'); ?>
        <?php } ?>
    </div>
    <?php } ?>
      

    
	<?php if (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && reference::product($product_id)->preowned) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	  
      <?php } else if (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
      <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Yellow.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_pre_-_owned', 'Pre-owned'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>		  

	  <?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Yellow.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_pre_-_owned', 'Pre-owned'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
    <?php } ?>
      </div>
    <?php } ?>
      

    
	<?php if  ($campaign_price && ($quantity > 0 || (!empty($product->sold_out_status['orderable'])) && (!empty(reference::product($product_id)->addtocart)))) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if ((reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) 
	  || (!empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->wholesale_soldout)))
	  || (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (!empty($campaign_price) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])))
	  ) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>

	  <?php } else if (!empty($campaign_price) && (!empty(reference::product($product_id)->no_customer_group_prices))) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?>	  

	  <?php } else if (!empty($campaign_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty(reference::product($product_id)->backorder)))) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Orange Red.jpg');" value="true" type="submit">
	  <span style="color: #b6ff9c;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_backorder', 'Insane Backorder'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 
	  
      <?php } else if ((empty(customer::$data['id']) && (!empty($campaign_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty(reference::product($product_id)->sign_in_date_price_prices))))) 
      || (empty(customer::$data['id']) && (!empty($campaign_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty(reference::product($product_id)->sign_in_date_price_prices)))))
      || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))) && (!empty(reference::product($product_id)->sign_in_deal) && (!empty(reference::product($product_id)->addtocart))))
      || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))) && (!empty(reference::product($product_id)->sign_in_deal) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s')) && (!empty(reference::product($product_id)->addtocart))))
      ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>		  

      <?php } else if  (!empty($campaign_price) && (!empty(reference::product($product_id)->preorderable) )) { ?> 
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button> 

      <?php } else if ((!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty($campaign_price))) 
      || (!empty(reference::product($product_id)->master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))))
      || (!empty(reference::product($product_id)->disable_master_insane_deal_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price))))
      || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 

      <?php } else if ((reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && (!empty(reference::product($product_id)->newarrival))) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	  <span style="color:  #f6cd0f;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>

      <?php } else if (((!empty(reference::product($product_id)->specialprice || disable_master_guest_special_price) && (!empty(reference::product($product_id)->master_guest_special_price) && (!empty(reference::product($product_id)->guest_price_prices)))) || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price)))))) 
      || (empty(customer::$data['id']) && (!empty(reference::product($product_id)->disable_master_guest_special_price)))
      ) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true" type="submit">
	  <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button>	 
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?>  



      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      <?php } ?>
    </div>
    <?php } ?>
      

	<?php if (!empty(reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->addtocart)) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>
      
	  <?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])))) { 
	  if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');opacity: 0.5;" value="true" type="submit" disabled="disabled">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); } ?>      

	  <?php } else if ((reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) 
	  || (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))
	  ) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
	  <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	  
	  <?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])))) { 
	  if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');opacity: 0.5;" value="true" type="submit" disabled="disabled">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); } ?>	  

      <?php } else if (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (!empty(reference::product($product_id)->stock_quantity_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))) { ?> 
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>

      <?php } else if (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))) { ?>      
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>  	  

      <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->restock)) && ((!empty(reference::product($product_id)->sign_in_dates) && (!empty(reference::product($product_id)->sign_in_date_price_prices)))) ) { ?>  
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Pink.jpg');" value="true" type="submit">
	  <span style="color:  #A5FFFF;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_restock', 'Restocked'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?>

	  <?php } else if (reference::product($product_id)->date_valid_from >= date('Y-m-d H:i:s') && reference::product($product_id)->restock) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Pink.jpg');" value="true" type="submit">
	  <span style="color:  #A5FFFF;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_restock', 'Restocked'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?>
	  
      <?php } else if ((empty(customer::$data['id']) && (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in))) 
      || (!empty(reference::product($product_id)->newarrival) && ((!empty(reference::product($product_id)->sign_in_dates) && (!empty(reference::product($product_id)->sign_in_date_price_prices)))))
      ) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	  <span style="color:  #f6cd0f;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>

      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Purple.jpg');" value="true" type="submit">
	  <span style="color:  #f6cd0f;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_new_arrivals', 'New Arrivals'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
     <?php } ?>
      </div>
     <?php } ?>
      

    <?php if (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && reference::product($product_id)->addtocart) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

      <?php } else if (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>	  

	  <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>

	  <?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])))) { 
	  if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');opacity: 0.5;" value="true" type="submit" disabled="disabled">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); } ?>
	   
	   
	  
      <?php } else if ((!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))
      || (!empty(customer::$data['enable_wholesale_price']) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->wholesale_soldout || ($quantity <= 0)))))
      || (!empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->wholesale_soldout || ($quantity <= 0)))))
      || (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices) && (!empty(reference::product($product_id)->stock_quantity_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      ) { ?> 
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button> 
	  
      <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) ) { ?>  
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      
	  <?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])))) { 
	  if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');opacity: 0.5;" value="true" type="submit" disabled="disabled">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Black.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_price', 'V.I.P Price');?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); } ?>      
      
      <?php } else if ((!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)))
      || (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable']))))
      || (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable'])
      ) { ?> 
      <!-- NONE -->
	  
	  <?php } else if (!empty(reference::product($product_id)->no_customer_group_prices)) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>	

      <?php } else if ((empty(customer::$data['id']) && ($quantity > 0 || !empty($product->sold_out_status['orderable'])) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in)))) 
      ||(empty(customer::$data['id']) &&  ((!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal))) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in))))
      ) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true" type="submit">
      <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
      <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button>	 	  

      <?php } else if ((!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))) 
      || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      ) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Red.jpg');" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      
      <?php } else if ((!empty(customer::$data['id']) && ((!empty(reference::product($product_id)->customer_specialprice || disable_master_customer_special_price) && (!empty(reference::product($product_id)->master_customer_special_price) && (!empty(reference::product($product_id)->default_price_price)))))) 
      || (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      || (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))
      || (!empty(customer::$data['id']) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      || (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (!empty(reference::product($product_id)->customer_group_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))
      ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Goldleaf.jpg');" value="true" type="submit">
      <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
      <b><i><?php echo language::translate('title_special_offer', 'Special Offer'); ?></i></b></button>	  	  
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?>

      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Green.jpg');" value="true">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	  <b><i><?php echo language::translate('title_add_to_cart', 'Add To Cart'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      <?php } ?>
    </div>
    <?php } ?>
      

    
	<?php if (reference::product($product_id)->date_valid_from <= date('Y-m-d H:i:s') && reference::product($product_id)->preorderable) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>
      
	  <?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>

      <?php } else if (!empty(reference::product($product_id)->preorderable) && (reference::product($product_id)->quantity <= 0|| reference::product($product_id)->sold_out_status['orderable'])){ ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>

      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
		
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" value="true">
	  <b><i><span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
      
    <?php } ?>
    </div>
    <?php } ?>
      

	<?php if (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->preorderable) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>
      
  
 	  

	  <?php } else if ((reference::product($product_id)->quantity <= 0|| reference::product($product_id)->sold_out_status['orderable']) 
	  || (!empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->wholesale_soldout)))
	  || (!empty(reference::product($product_id)->fake_sold_out) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))
	  ){ ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
      <?php } else if ((reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (empty(customer::$data['id']) && (!empty(reference::product($product_id)->pending_guest)))) 
      || (reference::product($product_id)->date_valid_to > date('Y-m-d H:i:s') && (!empty(customer::$data['id']) && (!empty(customer::$data['code']) && (!empty(reference::product($product_id)->pending_guest)))))
      ) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Lightblue.jpg');" value="true">
	  <span style="color: #0000c7;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_pending', 'Pending'); ?></i></b></button> 
	  
	  <?php } else if (!empty($campaign_price) && (!empty(reference::product($product_id)->no_customer_group_prices))) { ?>
      <!-- NONE -->

      <?php } else if ((!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->guest_insaneprice) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))) 
      || (!empty(customer::$data['id']) && (empty(customer::$data['code']) && (empty(customer::$data['enable_wholesale_price']) && (!empty(reference::product($product_id)->preorderable)  && (!empty(reference::product($product_id)->customer_insaneprice) && (!empty(reference::product($product_id)->customer_specialprice) && (!empty(reference::product($product_id)->default_price_price && ($quantity > 0 || !empty($product->sold_out_status['orderable']))))))))))
      ) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue Red.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_preorder', 'Insane Pre-Order !!'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 

	  <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->insaneprice) && (!empty($campaign_price)) && (!empty(reference::product($product_id)->sign_in_deal) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s')) ))) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Blue Red.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_preorder', 'Insane Pre-Order !!'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 

	  <?php } else if (!empty($campaign_price) && (!empty(reference::product($product_id)->insaneprice) && (!empty(reference::product($product_id)->preorderable)))) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue Red.jpg');" value="true" type="submit">
	  <span style="color:  #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_preorder', 'Insane Pre-Order !!'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 

      <?php } else if (empty(customer::$data['id']) && (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && reference::product($product_id)->prebackorder) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Purple Blue.jpg');" value="true" type="submit">
	  <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_pre_-_backorder', 'Pre-Backorder'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>		  

	  <?php } else if (reference::product($product_id)->date_valid_to <= date('Y-m-d H:i:s') && reference::product($product_id)->prebackorder) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>	  
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Purple Blue.jpg');" value="true" type="submit">
	  <span style="color: #ffffff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
      <b><i><?php echo language::translate('title_pre_-_backorder', 'Pre-Backorder'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>

	  <?php } else if (!empty(customer::$data['vip']) && (!empty(reference::product($product_id)->vip) && (empty(reference::product($product_id)->no_customer_group_prices) && (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->fake_sold_out_date_price_prices)) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable']))))) {
	  if(isset($vip_purchased) && !empty($vip_purchased)) { ?>
	  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg');opacity: 0.5;" value="true" type="submit" disabled="disabled">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_order', 'V.I.P Order'); ?></i></b></button>
	  <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Pure White.jpg');" value="true" type="submit">
	  <span style="color:  #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_vip_order', 'V.I.P Order'); ?></i></b></button>
	  <?php echo functions::form_draw_form_end('buy_now_form'); } ?>

      <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->preorderable)  
	  && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices)) && (!empty(reference::product($product_id)->sign_in)) ) )) { ?> 
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit">
      <span style="color: #cc0000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
      <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button>	  
 
      <?php } else if (!empty(reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->specialprice) && (!empty(reference::product($product_id)->guest_price_prices && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))))) { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit">
      <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
      <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button>	  	  
	  <?php echo functions::form_draw_form_end('buy_now_form'); ?> 
      
      <?php } else if (empty(customer::$data['id']) && (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->preorderable) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>	  
    
      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="background-image:url('/images/customize/Blue.jpg');" value="true" type="submit"
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_preorder', 'Pre-Order'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
    <?php } ?>
    </div>
    <?php } ?>
      

	<?php if (reference::product($product_id)->date_valid_from < date('Y-m-d H:i:s') && reference::product($product_id)->backorder) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button>
	   
      <?php } else if (!empty($campaign_price && ($quantity <= 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>	   

	  <?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
      <?php } else if (empty(customer::$data['id']) && (!empty(reference::product($product_id)->sign_in_date_price_prices)) ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Orange.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_backorder', 'Backorder'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>	  
	  
      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Gray.jpg');" value="true">
	  <b><i><span style="color: #fcbd35;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <?php echo language::translate('title_order_closed', 'Order Closed'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
     <?php } ?>
     </div>
     <?php } ?>
      

    <?php if (reference::product($product_id)->date_valid_from > date('Y-m-d H:i:s') && reference::product($product_id)->backorder) { ?>
	
    <div class="buy-now-wrapper">
      <?php if (!empty(reference::product($product_id)->options)) { ?>

	  <?php } else if (!empty($campaign_price && ($quantity > 0 || !empty($product->sold_out_status['orderable'])))) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" name="add_cart_product" style="display: none;" value="true" type="submit">
	  <span style="color: #fff;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_insane_deal_!!', 'Insane Deal !!'); ?></i></b></button> 	  

	  <?php } else if (reference::product($product_id)->quantity <= 0 || reference::product($product_id)->sold_out_status['orderable']) { ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="sold_out" disabled="disabled" name="add_cart_product" style="background-image:url('/images/customize/Dark Red.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i> <?php echo language::translate('title_sold_out', 'Sold Out'); ?></i></b></button>
	  
      <?php } else if (empty(customer::$data['id']) && ((!empty(reference::product($product_id)->sign_in_dates) && (!empty(reference::product($product_id)->sign_in_date_price_prices)))) ) { ?> 
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>      
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-default btn-block" disabled="disabled"  name="add_cart_product" style="background-image:url('/images/customize/Orange.jpg');" value="true" type="submit">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?>
	  <b><i><?php echo language::translate('title_backorder', 'Backorder'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>		  
	  
      <?php } else { ?>
      <?php echo functions::form_draw_form_begin('buy_now_form', 'post', document::href_ilink('checkout')); ?>
      <?php echo functions::form_draw_hidden_field('product_id', $product_id); ?>
      
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="btn btn-success btn-block" type="submit" name="add_cart_product" style="background-image:url('/images/customize/Orange.jpg');" value="true">
	  <span style="color: #000000;"><?php echo functions::draw_fonticon('fa-shopping-cart'); ?> 
	  <b><i><?php echo language::translate('title_backorder', 'Backorder'); ?></i></b></button>
      <?php echo functions::form_draw_form_end('buy_now_form'); ?>
    <?php } ?>
    </div>
    <?php } ?>
      
   

     <?php if (!empty(reference::product($product_id)->options) && (reference::product($product_id)->quantity > 0 || reference::product($product_id)->sold_out_status['orderable'])) { ?>
       <div class="buy-now-wrapper">

      <?php if (!empty(reference::product($product_id)->options) && (!empty($campaign_price))) { ?>
      <a class="add-cart-item btn btn-default btn-block" style="background-image:url('/images/customize/Red.jpg');" href="<?php echo document::href_ilink('product', array('product_id' => $product_id)); ?>">
	  <b><i><span style="color: #fff;"><?php echo functions::draw_fonticon('fa-list-ul'); ?> <?php echo language::translate('title_view_options', 'View Options'); ?></i></b></a> 

      <?php } else { ?>
      <a class="add-cart-item btn btn-default btn-block" style="background-image:url('/images/customize/Blue.jpg');" href="<?php echo document::href_ilink('product', array('product_id' => $product_id)); ?>">
	  <b><i><span style="color: #fff;"><?php echo functions::draw_fonticon('fa-list-ul'); ?> <?php echo language::translate('title_view_options', 'View Options'); ?></i></b></a>  
    <?php } ?>
    </div>
    <?php } ?>
      

          <?php } ?>
          </a>
      
  </a>

  
			  <button <?php echo !empty(customer::$data['id']) 
			  || (empty(reference::product($product_id)->signin))
			  || (!empty(reference::product($product_id)->signin) && (!empty(reference::product($product_id)->disable_sign_in)))
			   ? '':'disabled="disabled"' ?>
			  
		   class="preview btn btn-default btn-sm" data-toggle="lightbox" data-target="<?php echo htmlspecialchars($link) ?>" data-require-window-width="768" data-max-width="980">
    <?php echo functions::draw_fonticon('fa-search-plus'); ?>
  </button>

          <button class="wish"><i class="fa fa-heart <?php echo !isset($wishable)?(empty(customer::$data['id'])&&in_array($product_id, session::$data['wishlist'])?"wished":""): "wished"; ?>" aria-hidden="true"></i><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></button>
          <button class="shopee">
          <?php if (!empty(reference::product($product_id)->shopee) && (reference::product($product_id)->quantity > 0)) { ?> 
            <a class= <a href="<?php echo $shopee ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Shopee Logo Small.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Shopee Logo Small.png" /></a>
          <?php } ?> 
          
          <span class="visible-xs"><button class="lazada">
          <?php if (!empty(reference::product($product_id)->lazada) && (reference::product($product_id)->quantity > 0)) { ?>   
            <a class= <a href="<?php echo $lazada ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Lazada Logo Small.png" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Lazada Logo Small.png" /></a>
          <?php } ?>
          
          <button class="shopee_backend">
           <?php if (!empty(user::$data['status']) && (!empty(reference::product($product_id)->shopee_backend))) { ?> 
           <a class=<a href="<?php echo $shopee_backend ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Shopee Backend Small.jpg" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Shopee Backend Small.jpg" /></a>           
           <?php } ?>
           
          <button class="lazada_backend"> 
           <?php if (!empty(user::$data['status']) && (!empty(reference::product($product_id)->lazada_backend))) { ?>    
           <a class=<a href="<?php echo $lazada_backend ?>" target=\\"_blank\\"> <img class="image img-responsive" src="<?php echo WS_DIR_IMAGES; ?>Lazada Backend Small.jpg" srcset="" alt="<?php echo WS_DIR_IMAGES; ?>Lazada Backend Small.jpg" /></a> 
           <?php } ?>           
      
</article>

          <?php } ?>
          
      

          <?php } ?>
          
      
