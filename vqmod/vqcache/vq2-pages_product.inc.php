<?php

  if (empty($_GET['product_id'])) {
    http_response_code(400);
    echo 'Missing product_id';
    exit;
  }

  $product = reference::product($_GET['product_id']);

      
      
            if (!empty(user::$data['status']) || (customer::$data['id'] == 4640) || (customer::$data['id'] == 4653)) {
               $product_env = new ent_product($product->id);
               
            }
      

  if (empty($product->id)) {
    http_response_code(410);
    echo language::translate('error_410_gone', 'The requested file is no longer available');
    return;
  }

  if (empty($product->status)) {
    echo language::translate('error_404_not_found', 'The requested file could not be found');
    http_response_code(404);
    return;
  }

  

      



  

      





  if ($product->preorderable && $product->date_valid_from > date('Y-m-d H:i:s')) {
    notices::add('notices', sprintf(language::translate('notice_product_available_for_preordering', 'The product is available for preordering but will not ship until release date %s'), strftime(language::$selected['format_date'], strtotime($product->date_valid_from))), 'preorder');
  }

  if ($product->pending_guest && $product->date_valid_to > date('Y-m-d H:i:s') || ($product->pending)) {
    notices::add('notices', sprintf(language::translate('notice_product_available_for_pending_guest', 'The product is available for prebackorder but will not ship until release date %s'), strftime(language::$selected['format_date'], strtotime($product->date_valid_from))), 'preorder');
  }   
  
  if ($product->prebackorder && $product->date_valid_to < date('Y-m-d H:i:s')) {
    notices::add('notices', sprintf(language::translate('notice_product_available_for_prebackorder', 'The product is available for prebackorder but will not ship until release date %s'), strftime(language::$selected['format_date'], strtotime($product->date_valid_from))), 'preorder');
  }  
  
  if ($product->backorder && $product->date_valid_from > date('Y-m-d H:i:s')) {
    notices::add('notices', sprintf(language::translate('notice_product_available_for_backorder', 'The product is available for backorder but will not ship until release date %s'), strftime(language::$selected['format_date'], strtotime($product->date_valid_from))), 'backorder');
  } 
      
  if (empty($_GET['category_id']) && empty($product->manufacturer)) {
    if ($product->category_ids) {
      $category_ids = array_values($product->category_ids);
      $_GET['category_id'] = array_shift($category_ids);
    }
  }

  database::query(
    "update ". DB_TABLE_PRODUCTS ."
    set views = views + 1
    where id = ". (int)$_GET['product_id'] ."
    limit 1;"
  );

  if (!empty($_GET['category_id'])) {
    foreach (functions::catalog_category_trail($_GET['category_id']) as $category_id => $category_name) {
      document::$snippets['title'][] = $category_name;
    }
  } else if (!empty($product->manufacturer)) {
    document::$snippets['title'][] = $product->manufacturer->name;
  }

  document::$snippets['title'][] = $product->head_title ? $product->head_title : $product->name;
  document::$snippets['description'] = $product->meta_description ? $product->meta_description : strip_tags($product->short_description);
  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('product', array('product_id' => (int)$product->id), false) .'" />';

  if (!empty($product->image)) {
    document::$snippets['head_tags'][] = '<meta property="og:image" content="'. document::link('images/' . $product->image) .'"/>';
  }

  if (!empty($_GET['category_id'])) {
    breadcrumbs::add(language::translate('title_categories', 'Categories'), document::ilink('categories'));
    foreach (functions::catalog_category_trail($_GET['category_id']) as $category_id => $category_name) {
      breadcrumbs::add($category_name, document::ilink('category', array('category_id' => (int)$category_id)));
    }
  } else if (!empty($product->manufacturer)) {
    breadcrumbs::add(language::translate('title_manufacturers', 'Manufacturers'), document::ilink('manufacturers'));
    breadcrumbs::add($product->manufacturer->name, document::ilink('manufacturer', array('manufacturer_id' => $product->manufacturer->id)));
  }
  breadcrumbs::add($product->name);

  functions::draw_lightbox();

// Recently viewed products
  if (isset(session::$data['recently_viewed_products'][$product->id])) {
    unset(session::$data['recently_viewed_products'][$product->id]);
  }

  if (empty(session::$data['recently_viewed_products']) || !is_array(session::$data['recently_viewed_products'])) {
    session::$data['recently_viewed_products'] = array();
  }

  session::$data['recently_viewed_products'][$product->id] = array(
    'id' => $product->id,
    'name' => $product->name,
    'image' => $product->image,
  );

// Page
  $_page = new ent_view();

  $schema_json = array(
    '@context' => 'http://schema.org/',
    '@type' => 'Product',
    'productID' => $product->id,
    'sku' => $product->sku,
    'gtin14' => $product->gtin,
    'mpn' => $product->mpn,
    'name' => $product->name,
    'image' => document::link(!empty($product->image) ? 'images/' . $product->image : 'images/no_image.png'),
    'description' => !empty($product->description) ? strip_tags($product->description) : '',
    'offers' => array(
      '@type' => 'Offer',
      'priceCurrency' => currency::$selected['code'],
      'price' => (isset($product->campaign['price']) && $product->campaign['price'] > 0) ? tax::get_price($product->campaign['price'], $product->tax_class_id) : tax::get_price($product->price, $product->tax_class_id),
      'priceValidUntil' => (!empty($product->campaign) && strtotime($product->campaign['end_date']) > time()) ? $product->campaign['end_date'] : null,
      //'itemCondition' => 'http://schema.org/UsedCondition',
      //'availability' => 'http://schema.org/InStock',
      'url' => document::link(),
    ),
  );

  list($width, $height) = functions::image_scale_by_width(480, settings::get('product_image_ratio'));

  $_page->snippets = array(
    'product_id' => $product->id,
    'link' => document::ilink('product', array(), true),
    'code' => $product->code,
    'sku' => $product->sku,
    'mpn' => $product->mpn,
    'gtin' => $product->gtin,

    'min_qty' => $product->min_qty,
      	

    'max_qty' => $product->max_qty,
      	
    'name' => $product->name,
    'short_description' => !empty($product->short_description) ? $product->short_description : '',

    'date_valid_from_closing' => !empty($product->date_valid_from_closing) ? $product->date_valid_from_closing : '',
    'date_valid_to_closing' => !empty($product->date_valid_to_closing) ? $product->date_valid_to_closing : '',
    'medium_description' => !empty($product->medium_description) ? $product->medium_description : '',
    'costing_information' => !empty($product->costing_information) ? $product->costing_information : '',
    'oversize_parcel' => !empty($product->oversize_parcel) ? $product->oversize_parcel : '',
    'medium_parcel' => !empty($product->medium_parcel) ? $product->medium_parcel : '',
    'small_parcel' => !empty($product->small_parcel) ? $product->small_parcel : '',
    'opening_quantity' => !empty($product->opening_quantity) ? $product->opening_quantity : '',
    'listing_info' => !empty($product->listing_info) ? $product->listing_info : '',
    'box_conditions' => !empty($product->box_conditions) ? $product->box_conditions : '',
    'guess_price' => !empty($product->guess_price) ? $product->guess_price : '',
    'shopee' => !empty($product->shopee) ? $product->shopee : '',
    'lazada' => !empty($product->lazada) ? $product->lazada : '',
    'shopee_backend' => !empty($product->shopee_backend) ? $product->shopee_backend : '',
    'lazada_backend' => !empty($product->lazada_backend) ? $product->lazada_backend : '',    
      
    'description' => !empty($product->description) ? $product->description : '<em style="opacity: 0.65;">'. language::translate('text_no_product_description', 'There is no description for this product yet.') . '</em>',
    'technical_data' => !empty($product->technical_data) ? preg_split('#\r\n|\r|\n#', $product->technical_data) : array(),
    'head_title' => !empty($product->head_title) ? $product->head_title : $product->name,
    'meta_description' => !empty($product->meta_description) ? $product->meta_description : $product->short_description,
    'attributes' => $product->attributes,
    'keywords' => $product->keywords,
    'image' => array(
      'original' => ltrim(!empty($product->images) ? 'images/' . $product->image : 'images/no_image.png', '/'),
      'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $product->image, $width, $height, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $product->image, $width*2, $height*2, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'viewport' => array(
        'width' => $width,
        'height' => $height,
      ),
    ),
    'sticker' => '',

            'wishable' => $product->wishable,
            
    'extra_images' => array(),
    'manufacturer' => array(),
    'regular_price' => tax::get_price($product->price, $product->tax_class_id),
    'campaign_price' => (isset($product->campaign['price']) && $product->campaign['price'] > 0) ? tax::get_price($product->campaign['price'], $product->tax_class_id) : null,
    'tax_class_id' => $product->tax_class_id,
    'including_tax' => !empty(customer::$data['display_prices_including_tax']) ? true : false,
    'total_tax' => tax::get_tax(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id),
    'tax_rates' => array(),
    'quantity' => @round($product->quantity, $product->quantity_unit['decimals']),
    'quantity_unit' => $product->quantity_unit,
    'stock_status' => settings::get('display_stock_count') ? language::number_format($product->quantity, $product->quantity_unit['decimals']) .' '. $product->quantity_unit['name'] : language::translate('title_in_stock', 'In Stock'),
    'delivery_status' => !empty($product->delivery_status['name']) ? $product->delivery_status['name'] : '',
    'sold_out_status' => !empty($product->sold_out_status['name']) ? $product->sold_out_status['name'] : '',
    'orderable' => $product->sold_out_status['orderable'],

    'preorderable' => ($product->preorderable && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'backorder' => ($product->backorder && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'insanedeal' => ($product->insanedeal && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'insaneprice' => ($product->insaneprice && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'forbidden' => ($product->forbidden && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'newarrival' => ($product->newarrival && $product->date_valid_from > date('Y-m-d H:i:s')) ? true : false,
    'preowned' => ($product->preowned&& $product->date_valid_from > date('')) ? true : false,
    'restock' => ($product->restock && $product->date_valid_from <=> date('')) ? true : false,
    'addtocart' => ($product->addtocarte && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'specialoffer' => ($product->specialoffer && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'pending' => ($product->pending && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'pending_guest' => ($product->pending_guest && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,    
    'hidden' => ($product->hidden && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'hide_product' => ($product->hide_product && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'master_guest_special_price' => ($product->master_guest_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'master_customer_special_price' => ($product->master_customer_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'master_wholesale_special_price' => ($product->master_wholesale_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'master_insane_deal_price' => ($product->master_insane_deal_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'disable_master_guest_special_price' => ($product->disable_master_guest_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'disable_master_customer_special_price' => ($product->disable_master_customer_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'disable_master_wholesale_special_price' => ($product->disable_master_wholesale_special_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'disable_master_insane_deal_price' => ($product->disable_master_insane_deal_price && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'customer_specialprice' => ($product->customer_specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'guest_insaneprice' => ($product->guest_insaneprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'customer_insaneprice' => ($product->customer_insaneprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'wholesale_specialprice' => ($product->wholesale_specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'specialprice' => ($product->specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'guest_insaneprice' => ($product->guest_insaneprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,    
    'customer_specialprice' => ($product->customer_specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'customer_insaneprice' => ($product->customer_insaneprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'wholesale_specialprice' => ($product->wholesale_specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,  
    'wholesale_soldout' => ($product->wholesale_soldout && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false, 
    'disable_wholesale_soldout' => ($product->disable_wholesale_soldout && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false, 
    'signin' => ($product->signin && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'sign_in' => ($product->sign_in && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'sign_in_deal' => ($product->sign_in_deal && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'no_customer_group_prices' => ($product->sign_in_deal && $product->date_valid_from <=> date('Y-m-d H:i:s')) ? true : false,
    'signin' => ($product->signin && $product->date_valid_to <=> date('Y-m-d H:i:s')) ? true : false,    
    'prebackorder' => ($product->prebackorder && $product->date_valid_to < date('Y-m-d H:i:s')) ? true : false,
    'disable_sign_in' => ($product->disable_sign_in && $product->date_valid_to <=> date('Y-m-d H:i:s')) ? true : false,    
      
    'cheapest_shipping_fee' => null,
    'catalog_only_mode' => settings::get('catalog_only_mode'),

            'customer_group_prices' => isset($product_env)?$product_env->data['customer_group_prices']:null,
      
    'options' => array(),
  );


          if(!empty(customer::$data['id']) && !empty(customer::$data['vip'])){
                  $previous_vip_order_query = database::query(
                  "select * from ". DB_TABLE_VIPLIST ."
                  where product_id = ". (int)$product->id ." and customer_id = ". (int)customer::$data['id'] .";");
                  
                  if(database::num_rows($previous_vip_order_query) > 0){
                     $_page->snippets['vip_purchased'] = true;
                  }
                  else{
                      $_page->snippets['vip_purchased'] = false;
                  }
            }
      
// Images for Options
  list($width, $height) = functions::image_scale_by_width(480, settings::get('product_image_ratio'));
  foreach ($product->images as $productImageId => $image) {
    $_page->snippets['images_for_options'][] = array(
      'id' => $productImageId,
      'original' => ltrim(WS_DIR_IMAGES . $image, '/'),
      'thumbnail' => functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $image, $width, $height, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'thumbnail_2x' => functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $image, $width*2, $height*2, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'viewport' => array(
        'width' => $width,
        'height' => $height,
      ),
    );
  }

  if ($product->preorderable && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_preorder', 'Pre-Order');
  }
  
  if ($product->backorder && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_backorder', 'backorder');
  }
  
  if ($product->addtocart && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_addtocart', 'addtocart');
  }
  
  if ($product->pending && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_pending', 'pending');
  }  
  
  if ($product->pending_guest && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_pending_guest', 'Pending Guest');
  }   
  
  if ($product->insanedeal && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_insanedeal', 'insanedeal');
  } 
  
  if ($product->specialoffer && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_specialoffer', 'specialoffer');
  }
  
  if ($product->forbidden && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_forbidden', 'forbidden');
  }   
  
  if ($product->prebackorder && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_prebackorder', 'prebackorder');
  }   

  if ($product->hidden && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_hidden', 'hidden');
  }   
  
  if ($product->hide_product && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_hide_product', 'Hide Product');
  }   
  
  if ($product->specialprice && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_specialprice', 'Special Price');
  }  
  
  if ($product->sign_in && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_sign_in', 'Sign In');
  }  
  
  if ($product->signin && $product->date_valid_from <=> date('Y-m-d H:i:s') && ($product->quantity > 0 && !empty($product->sold_out_status['orderable']))) {
    $box_product->snippets['stock_status_value'] = language::translate('title_signin', 'Sign In');
  }   
      
// Extra Images
  list($width, $height) = functions::image_scale_by_width(160, settings::get('product_image_ratio'));
  foreach (array_slice(array_values($product->images), 1) as $image) {
    $_page->snippets['extra_images'][] = array(
      'original' => 'images/' . $image,
      'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $image, $width, $height, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $image, $width*2, $height*2, settings::get('product_image_clipping'), settings::get('product_image_trim')),
      'viewport' => array(
        'width' => $width,
        'height' => $height,
      ),
    );
  }

// Watermark Images
  if (settings::get('product_image_watermark')) {
    $_page->snippets['image']['original'] = functions::image_process(FS_DIR_APP . $_page->snippets['image']['original'], array('watermark' => true));
    foreach (array_keys($_page->snippets['extra_images']) as $key) {
      $_page->snippets['extra_images'][$key]['original'] = functions::image_process(FS_DIR_APP . $_page->snippets['extra_images'][$key]['original'], array('watermark' => true));
    }
  }

// Stickers
  if (!empty($product->campaign['price'])) {
    $percentage = round(($product->price - $product->campaign['price']) / $product->price * 100);
    $_page->snippets['sticker'] = '<div class="sticker sale" title="'. language::translate('title_on_sale', 'On Sale') .'">'. language::translate('sticker_sale', 'Sale') .'<br />-'. $percentage .' %</div>';
  } else if ($product->date_created > date('Y-m-d', strtotime('-'.settings::get('new_products_max_age')))) {
    $_page->snippets['sticker'] = '<div class="sticker new" title="'. language::translate('title_new', 'New') .'">'. language::translate('sticker_new', 'New') .'</div>';
  }

// Manufacturer
  if (!empty($product->manufacturer)) {
    $schema_json['brand']['name'] = $product->manufacturer->name;

    $_page->snippets['manufacturer'] = array(
      'id' => $product->manufacturer->id,
      'name' => $product->manufacturer->name,
      'image' => array(),
      'link' => document::ilink('manufacturer', array('manufacturer_id' => $product->manufacturer->id)),
    );

    if (!empty($product->manufacturer->image)) {
      $_page->snippets['manufacturer']['image'] = array(
        'original' => 'images/' . $product->manufacturer->image,
        'thumbnail' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $product->manufacturer->image, 200, 60),
        'thumbnail_2x' => functions::image_thumbnail(FS_DIR_APP . 'images/' . $product->manufacturer->image, 400, 120),
        'viewport' => array(
          'width' => $width,
          'height' => $height,
        ),
      );
    }
  }


// Quantity Prices
  $_page->snippets['quantity_prices'] = array();
  foreach($product->quantity_prices as $qty => $price) {
    $_page->snippets['quantity_prices'][] = array(
      'quantity' => (float)$qty,
      'price' => tax::get_price($price, $product->tax_class_id),
    );
  }
      
// Tax
  $tax_rates = tax::get_tax_by_rate(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id);
  if (!empty($tax_rates)) {
    foreach ($tax_rates as $tax_rate) {
      $_page->snippets['tax_rates'][] = currency::format($tax_rate['tax']) .' ('. $tax_rate['name'] .')';
    }
  }

// Cheapest shipping
  if (settings::get('display_cheapest_shipping')) {

    $shipping = new mod_shipping('local');

    $shipping_items = array(
      array(
        'quantity' => 1,
        'product_id' => $product->id,
        'price' => !empty($product->campaign['price']) ? $product->campaign['price'] : $product->price,
        'tax' => tax::get_tax(!empty($product->campaign['price']) ? $product->campaign['price'] : $product->price, $product->tax_class_id),
        'tax_class_id' => $product->tax_class_id,
        'weight' => $product->weight,
        'weight_class' => $product->weight_class,
        'dim_x' => $product->dim_x,
        'dim_x' => $product->dim_x,
        'dim_y' => $product->dim_y,
        'dim_z' => $product->dim_z,
        'dim_class' => $product->dim_class,
      ),
    );

    $cheapest_shipping = $shipping->cheapest($shipping_items, currency::$selected['code'], customer::$data);

    if (!empty($cheapest_shipping)) {
      $_page->snippets['cheapest_shipping_fee'] = tax::get_price($cheapest_shipping['cost'], $cheapest_shipping['tax_class_id']);
    }
  }

// Options
  if (count($product->options) > 0) {
    foreach ($product->options as $group) {
      $values = '';
      switch ($group['function']) {

        case 'checkbox':

          foreach ($group['values'] as $value) {

            $price_adjust_text = '';
            $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
            $tax_adjust = currency::format_raw(tax::get_tax($value['price_adjust'], $product->tax_class_id));

            if ($value['price_adjust']) {
              $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
              if ($value['price_adjust'] > 0) $price_adjust_text = ' +' . $price_adjust_text;
            }

            $values .= '<div class="checkbox">' . PHP_EOL
                     . '  <label>' . functions::form_draw_checkbox('options['.$group['name'].'][]', $value['name'], true, 'data-image-id="'.(int)$value['image_id'].'" data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) .' '. $value['name'] . $price_adjust_text . '</label>' . PHP_EOL
                     . '</div>';
          }
          break;

        case 'input':

          $value = array_shift($group['values']);

          $price_adjust_text = '';
          $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
          $tax_adjust = currency::format_raw(tax::get_tax($value['price_adjust'], $product->tax_class_id));

          if ($value['price_adjust']) {
            $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            if ($value['price_adjust'] > 0) $price_adjust_text = ' +'.$price_adjust_text;
          }

          $values .= functions::form_draw_text_field('options['.$group['name'].']', isset($_POST['options'][$group['name']]) ? true : $value['value'], 'data-image-id="'.(int)$value['image_id'].'" data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) . $price_adjust_text . PHP_EOL;
          break;

        case 'radio':

          foreach ($group['values'] as $value) {

            $price_adjust_text = '';
            $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
            $tax_adjust = currency::format_raw(tax::get_tax($value['price_adjust'], $product->tax_class_id));

            if ($value['price_adjust']) {
              $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
              if ($value['price_adjust'] > 0) $price_adjust_text = ' +'.$price_adjust_text;
            }

            $values .= '<div class="radio">' . PHP_EOL
                     . '  <label>'. functions::form_draw_radio_button('options['.$group['name'].']', $value['name'], true, 'data-image-id="'.(int)$value['image_id'].'" data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"' . (!empty($group['required']) ? ' required="required"' : '')) .' '. $value['name'] . $price_adjust_text . '</label>' . PHP_EOL
                     . '</div>';
          }
          break;

        case 'select':

          $options = array(array('-- '. language::translate('title_select', 'Select') .' --', ''));
          foreach ($group['values'] as $value) {

            $price_adjust_text = '';
            $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
            $tax_adjust = currency::format_raw(tax::get_tax($value['price_adjust'], $product->tax_class_id));

            if ($value['price_adjust']) {
              $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
              if ($value['price_adjust'] > 0) $price_adjust_text = ' +'.$price_adjust_text;
            }

            $options[] = array($value['name'] . $price_adjust_text, $value['name'], 'data-image-id="'.(int)$value['image_id'].'" data-price-adjust="'. (float)$price_adjust .'" data-tax-adjust="'. (float)$tax_adjust .'"');
          }

          $values .= functions::form_draw_select_field('options['.$group['name'].']', $options, true, !empty($group['required']) ? 'required="required"' : '');
          break;

        case 'textarea':

          $value = array_shift($group['values']);

          $price_adjust_text = '';
          $price_adjust = currency::format_raw(tax::get_price($value['price_adjust'], $product->tax_class_id));
          $tax_adjust = currency::format_raw(tax::get_tax($value['price_adjust'], $product->tax_class_id));

          if ($value['price_adjust']) {
            $price_adjust_text = currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            if ($value['price_adjust'] > 0) {
              $price_adjust_text = ' <br />+'. currency::format(tax::get_price($value['price_adjust'], $product->tax_class_id));
            }
          }

          $values .= functions::form_draw_textarea('options['.$group['name'].']', isset($_POST['options'][$group['name']]) ? true : $value['value'], !empty($group['required']) ? 'required="required"' : '') . $price_adjust_text. PHP_EOL;
          break;
      }

      $_page->snippets['options'][] = array(
        'name' => $group['name'],
        'description' => $group['description'],
        'required' => !empty($group['required']) ? 1 : 0,
        'values' => $values,
      );
    }
  }

  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo $_page->stitch('pages/product.ajax');
  } else {
    echo $_page->stitch('pages/product');
  }

  document::$snippets['head_tags']['schema_json'] = '<script type="application/ld+json">'. json_encode($schema_json, JSON_UNESCAPED_SLASHES) .'</script>';
