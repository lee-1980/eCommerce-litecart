<?php

  if (!empty($_GET['product_id'])) {
    $product = new ent_product($_GET['product_id']);

  } elseif (!empty($_GET['clone_id'])) {
    $product = new ent_product($_GET['clone_id']);
    unset($_GET['clone_id']); //cleanup
    $product->data['campaigns'] = null;
    $product->data['categories'] = null;
    $product->data['customer_insaneprice'] = null;    
    $product->data['customer_specialprice'] = null;
    $product->data['description'] = null;
    $product->data['disable_master_customer_special_price'] = null;
    $product->data['disable_master_guest_special_price'] = null;
    $product->data['disable_master_insane_deal_price'] = null;
    $product->data['disable_master_wholesale_special_price'] = null;
    $product->data['disable_wholesale_soldout'] = null;
    $product->data['free_shipping'] = null;
    $product->data['guest_insaneprice'] = null;
    $product->data['head_title'] = null;
    $product->data['id'] = null;
    $product->data['image'] = null;
    $product->data['images'] = null;
    $product->data['insanedeal'] = null;
    $product->data['insaneprice'] = null;
    $product->data['master_customer_special_price'] = null;
    $product->data['master_guest_special_price'] = null;
    $product->data['master_insane_deal_price'] = null;
    $product->data['master_wholesale_special_price'] = null;
    $product->data['meta_description'] = null;
    $product->data['no_customer_group_prices'] = null;
    $product->data['no_free_shipping'] = null;
    $product->data['purchase_price'] = null;
    $product->data['sign_in'] = null;
    $product->data['sign_in'] = null;
    $product->data['sign_in_deal'] = null;
    $product->data['sign_in_deal'] = null;
    $product->data['sku'] = null;
    $product->data['specialprice'] = null;
    $product->data['wholesale_soldout'] = null;
    $product->data['wholesale_specialprice'] = null;
    $product->data['shopee'] = null;
    $product->data['shopee_backend'] = null;
    $product->data['lazada'] = null;
    $product->data['lazada_backend'] = null;
    $product->data['short_description'] = null;

    
    

    
    
            
  } else {
    $product = new ent_product();
  }

  if (empty($_POST)) {
    foreach ($product->data as $key => $value) {
      $_POST[$key] = $value;
    }

    $_POST['keywords'] = implode(',', $_POST['keywords']);
    if (empty($product->data['id']) && isset($_GET['category_id'])) $_POST['categories'][] = $_GET['category_id'];
  }

  breadcrumbs::add(!empty($product->data['id']) ? language::translate('title_edit_product', 'Edit Product') . ': '. $product->data['name'][language::$selected['code']] : language::translate('title_add_new_product', 'Add New Product'));

  
	if (isset($_POST['save']) || isset($_POST['save_stay'])) {
		

    try {
      if (empty($_POST['categories'])) $_POST['categories'] = array();
      if (empty($_POST['images'])) $_POST['images'] = array();

      if (empty($_POST['quantity_prices'])) $_POST['quantity_prices'] = array();
      

      if (empty($_POST['stock_quantity_guest_prices'])) $_POST['stock_quantity_guest_prices'] = array();      
      if (empty($_POST['stock_quantity_prices'])) $_POST['stock_quantity_prices'] = array();
      if (empty($_POST['default_price_prices'])) $_POST['default_price_prices'] = array();
      if (empty($_POST['guest_price_prices'])) $_POST['guest_price_prices'] = array();
      if (empty($_POST['sign_in_date_price_prices'])) $_POST['sign_in_date_price_prices'] = array();
      if (empty($_POST['fake_sold_out_date_price_prices'])) $_POST['fake_sold_out_date_price_prices'] = array();
	  if (empty($_POST['wholesale_price_prices'])) $_POST['wholesale_price_prices'] = array();
      

      if (empty($_POST['customer_group_prices'])) $_POST['customer_group_prices'] = array();
      
      if (empty($_POST['attributes'])) $_POST['attributes'] = array();

      if (!isset($_POST['preorderable'])) $_POST['preorderable'] = 0;
      if (!isset($_POST['backorder'])) $_POST['backorder'] = 0;
      if (!isset($_POST['addtocart'])) $_POST['addtocart'] = 0;
      if (!isset($_POST['pending'])) $_POST['pending'] = 0;
      if (!isset($_POST['pending_guest'])) $_POST['pending_guest'] = 0;
      if (!isset($_POST['insanedeal'])) $_POST['insanedeal'] = 0;
      if (!isset($_POST['insaneprice'])) $_POST['insaneprice'] = 0;
      if (!isset($_POST['prebackorder'])) $_POST['prebackorder'] = 0;
      if (!isset($_POST['signin'])) $_POST['signin'] = 0;
      if (!isset($_POST['forbidden'])) $_POST['forbidden'] = 0;
      if (!isset($_POST['newarrival'])) $_POST['newarrival'] = 0;
      if (!isset($_POST['master_guest_special_price'])) $_POST['master_guest_special_price'] = 0;
      if (!isset($_POST['master_customer_special_price'])) $_POST['master_customer_special_price'] = 0;      
      if (!isset($_POST['master_wholesale_special_price'])) $_POST['master_wholesale_special_price'] = 0;
      if (!isset($_POST['master_insane_deal_price'])) $_POST['master_insane_deal_price'] = 0; 
      if (!isset($_POST['disable_master_guest_special_price'])) $_POST['disable_master_guest_special_price'] = 0; 
      if (!isset($_POST['disable_master_customer_special_price'])) $_POST['disable_master_customer_special_price'] = 0;
      if (!isset($_POST['disable_master_wholesale_special_price'])) $_POST['disable_master_wholesale_special_price'] = 0;
      if (!isset($_POST['disable_master_insane_deal_price'])) $_POST['disable_master_insane_deal_price'] = 0;     
      if (!isset($_POST['specialoffer'])) $_POST['specialoffer'] = 0;
      if (!isset($_POST['customer_specialprice'])) $_POST['customer_specialprice'] = 0;
      if (!isset($_POST['guest_insaneprice'])) $_POST['guest_insaneprice'] = 0;
      if (!isset($_POST['customer_insaneprice'])) $_POST['customer_insaneprice'] = 0;
      if (!isset($_POST['wholesale_specialprice'])) $_POST['wholesale_specialprice'] = 0;
      if (!isset($_POST['wholesale_soldout'])) $_POST['wholesale_soldout'] = 0;
      if (!isset($_POST['disable_wholesale_soldout'])) $_POST['disable_wholesale_soldout'] = 0;
      if (!isset($_POST['preowned'])) $_POST['preowned'] = 0;
      if (!isset($_POST['restock'])) $_POST['restock'] = 0;
      if (!isset($_POST['hidden'])) $_POST['hidden'] = 0;
      if (!isset($_POST['hide_product'])) $_POST['hide_product'] = 0;
      if (!isset($_POST['specialprice'])) $_POST['specialprice'] = 0;
      if (!isset($_POST['sign_in'])) $_POST['sign_in'] = 0;
      if (!isset($_POST['disable_sign_in'])) $_POST['disable_sign_in'] = 0;
      if (!isset($_POST['sign_in_deal'])) $_POST['sign_in_deal'] = 0;
      if (!isset($_POST['free_shipping'])) $_POST['free_shipping'] = 0;
      if (!isset($_POST['no_free_shipping'])) $_POST['no_free_shipping'] = 0;
      if (!isset($_POST['webp'])) $_POST['webp'] = 0;
      if (!isset($_POST['no_customer_group_prices'])) $_POST['no_customer_group_prices'] = 0;
      if (!isset($_POST['vip'])) $_POST['vip'] = 0;
      if (!isset($_POST['fake_sold_out'])) $_POST['fake_sold_out'] = 0;

      
      if (empty($_POST['campaigns'])) $_POST['campaigns'] = array();
      if (empty($_POST['options'])) $_POST['options'] = array();
      if (empty($_POST['options_stock'])) $_POST['options_stock'] = array();

      if (empty($_POST['name'][language::$selected['code']])) throw new Exception(language::translate('error_must_enter_name', 'You must enter a name'));
      if (empty($_POST['categories'])) throw new Exception(language::translate('error_must_select_category', 'You must select a category'));

      if (!empty($_POST['code']) && database::num_rows(database::query("select id from ". DB_TABLE_PRODUCTS ." where id != '". (int)$product->data['id'] ."' and code = '". database::input($_POST['code']) ."' limit 1;"))) throw new Exception(language::translate('error_code_database_conflict', 'Another entry with the given code already exists in the database'));
      if (!empty($_POST['sku'])  && database::num_rows(database::query("select id from ". DB_TABLE_PRODUCTS ." where id != '". (int)$product->data['id'] ."' and sku = '". database::input($_POST['sku']) ."' limit 1;")))   throw new Exception(language::translate('error_sku_database_conflict', 'Another entry with the given SKU already exists in the database'));
      if (!empty($_POST['mpn'])  && database::num_rows(database::query("select id from ". DB_TABLE_PRODUCTS ." where id != '". (int)$product->data['id'] ."' and mpn = '". database::input($_POST['mpn']) ."' limit 1;")))   throw new Exception(language::translate('error_mpn_database_conflict', 'Another entry with the given MPN already exists in the database'));
      if (!empty($_POST['gtin']) && database::num_rows(database::query("select id from ". DB_TABLE_PRODUCTS ." where id != '". (int)$product->data['id'] ."' and gtin = '". database::input($_POST['gtin']) ."' limit 1;"))) throw new Exception(language::translate('error_gtin_database_conflict', 'Another entry with the given GTIN already exists in the database'));

      foreach ($_POST['options'] as $option) {
        if (empty($option['group_id']) || empty($option['value_id'])) {
          throw new Exception(language::translate('error_an_invalid_option', 'A provided option is invalid'));
        }
      }

      $_POST['keywords'] = explode(',', $_POST['keywords']);

      $fields = array(
        'status',

        'preorderable',
        'backorder',
        'addtocart',
        'pending',
        'pending_guest',
        'insanedeal',
        'insaneprice',
        'prebackorder',
        'signin',
        'forbidden',
        'newarrival',
        'specialoffer',
        'preowned',
        'restock',
        'hidden',
        'hide_product',
        'master_guest_special_price',
        'master_customer_special_price',
        'master_wholesale_special_price',
        'master_insane_deal_price',
        'disable_master_guest_special_price',
        'disable_master_guest_special_price',
        'disable_master_customer_special_price',
        'disable_master_wholesale_special_price',
        'disable_master_insane_deal_price',        
        'specialprice',
        'guest_insaneprice',
        'customer_specialprice',
        'customer_insaneprice',
        'wholesale_specialprice',
        'wholesale_soldout',
        'disable_wholesale_soldout',
        'sign_in',
        'sign_in_deal',
        'free_shipping',
        'no_free_shipping',
        'webp',
        'no_customer_group_prices',
        'disable_sign_in',
        'vip',
        'fake_sold_out',

      
        'manufacturer_id',
        'listing_link_id',
        'supplier_id',
        'delivery_status_id',
        'sold_out_status_id',
        'default_category_id',
        'categories',
        'attributes',
        'keywords',
        'date_valid_from',
        'date_valid_to',
        'quantity',
        'quantity_unit_id',
        'purchase_price',
        'purchase_price_currency_code',
        'prices',

    'quantity_prices',
      

    'stock_quantity_guest_prices',       
    'stock_quantity_prices',
    'default_price_prices',
    'guest_price_prices',
    'sign_in_date_price_prices',
	'wholesale_price_prices',
	'fake_sold_out_date_price_prices',
      

    'customer_group_prices',
      
        'campaigns',
        'tax_class_id',
        'code',
        'sku',
        'mpn',
        'gtin',
        'taric',
        'dim_x',
        'dim_y',
        'dim_z',
        'dim_class',
        'weight',
        'weight_class',
        'name',
        'short_description',

        'date_valid_from_closing',
        'date_valid_to_closing',
        'medium_description',
        'costing_information',
        'small_parcel',
        'oversize_parcel',
        'opening_quantity',
        'medium_parcel',
        'listing_info',
        'box_conditions',
        'guess_price',
        'shopee',
        'lazada',
        'shopee_backend',
        'lazada_backend',
      
        'description',
        'technical_data',
        'head_title',
        'meta_description',
        'images',
        'options',
        'options_stock',

        'min_qty',
      

        'max_qty',
      
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $product->data[$field] = $_POST[$field];
      }

      if (!empty($_FILES['new_images']['tmp_name'])) {
        foreach (array_keys($_FILES['new_images']['tmp_name']) as $key) {
          $product->add_image($_FILES['new_images']['tmp_name'][$key]);
        }
      }

      
      $product->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
	if (isset($_POST['save'])) {
		header('Location: '. document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc' => 'catalog', 'category_id' => $_POST['categories'][0])));
	}
	
	if (isset($_POST['save_stay'])) {
		header('Location: '. document::link(WS_DIR_ADMIN, array('product_id' => $product->data['id']), true));
	}	
		



      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (empty($product->data['id'])) throw new Exception(language::translate('error_must_provide_product', 'You must provide a product'));

      $product->delete();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc' => 'catalog', 'category_id' => $_POST['categories'][0])));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  list($product_image_width, $product_image_height) = functions::image_scale_by_width(320, settings::get('product_image_ratio'));

  functions::draw_lightbox();
?>
<style>
#categories {
  max-height: 310px;
  overflow-y: auto;
  overflow-x: hidden;
  transition: all 200ms linear;
}
#categories:hover {
  width: 150%;
  z-index: 999;
}
#categories label {
  white-space: nowrap;
}

#images .thumbnail {
  margin: 0;
}
#images .image {
  overflow: hidden;
}
#images .thumbnail {
  margin-right: 15px;
}
#images img {
  max-width: 50px;
  max-height: 50px;
}
#images .actions {
  text-align: right;
  padding: 0.25em 0;
}
</style>

      <style>
      .sticky {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
      }

      .container {
          width: 100%;
          max-width: 1200px;
      }
      .header {
          background: #ffffff00;
          position: relative;
          display: none;
          z-index: 2000;
      }
      .menu-btn {
          position: absolute;
          left: 10%;
          z-index: 2000;
          
      }
      .menu-btn a {
          display: inline-block;
          width: 90px;
          height: 35px;
          line-height: 35px;
          text-align: center;
          font-size: 14px;
          color: #fff;
          background: #F76753;
          text-decoration: none !important;
          border-radius: 6px 6px 6px 6px;
      }



      </style>      
      

<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo !empty($product->data['id']) ? language::translate('title_edit_product', 'Edit Product') . ': '. $product->data['name'][language::$selected['code']] : language::translate('title_add_new_product', 'Add New Product'); ?>
  </div>

  
        <div id="navbar">
        <ul class="nav nav-tabs">
          <li><a data-toggle="tab" href="#tab-general"><?php echo language::translate('title_general', 'General'); ?></a></li>
          <li><li><a data-toggle="tab" href="#tab-categories"><?php echo language::translate('title_categories', 'Categories'); ?></a></li>
          <li><a data-toggle="tab" href="#tab-information"><?php echo language::translate('title_information', 'Information'); ?></a></li>
          <li><a data-toggle="tab" href="#tab-attributes"><?php echo language::translate('title_attributes', 'Attributes'); ?></a></li>
          <li><a data-toggle="tab" href="#tab-prices"><?php echo language::translate('title_prices', 'Prices'); ?></a></li>
          <li><a data-toggle="tab" href="#tab-options"><?php echo language::translate('title_options', 'Options'); ?></a></li>
          <li><a data-toggle="tab" href="#tab-stock"><?php echo language::translate('title_stock', 'Stock'); ?></a></li>

        <li><a data-toggle="tab" href="#tab-wishlist"><?php echo language::translate('title_wishlist', 'WishList'); ?></a></li>
      
          <li>        
          <div class="header">
            <div class="container">
            <div class="navigation">
              <div class="row">
                    <div class="menu">
                      <div class="menu-list">
                          <iframe width="1100" height="320" frameborder="0" scrolling="no" src="https://onedrive.live.com/embed?resid=3C99177F71C250B3%2110354&authkey=%21AGZJi_iEprBtqsk&em=2&AllowTyping=True&wdInConfigurator=True"></iframe>
                      </div>
                    </div>        
                </div>
              </div>
            </div>
          </div>
          <div class="menu-btn">
            <a onclick="documentTrack('#');" href="#">Menu <i class="fa fa-chevron-down"></i></a>
          </div> 
        
          <div class="head">
            <div class="container">
          </div> 
          </li>
          </div> 
      








  
  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('product_form', 'post', false, true); ?>

      <div class="tab-content">
        <div id="tab-general" class="tab-pane active">

          <div class="row">
            <div class="col-md-4">

              <div class="form-group">
                <label><?php echo language::translate('title_status', 'Status'); ?></label>
                <?php echo functions::form_draw_toggle('status', isset($_POST['status']) ? $_POST['status'] : '0', 'e/d'); ?>
              </div>


<style>
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  
  height: auto; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<h2>Shopee</h2>
      <div class="form-group">
        <label><?php echo language::translate('title_shopee', 'Shopee'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'shopee['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>

      <div class="form-group">
        <label><?php echo language::translate('title_shopee_backend', 'Shopee Backend'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'shopee_backend['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>

<h2>Lazada</h2>
      <div class="form-group">
        <label><?php echo language::translate('title_lazada', 'Lazada'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'lazada['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>

      <div class="form-group">
        <label><?php echo language::translate('title_lazada_backend', 'Lazada Backend'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'lazada_backend['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>      
<h2>Options</h2>
<div class="row">
  <div class="column" style="width: 165px;">

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('webp', '1', true); ?> <?php echo language::translate('title_webp', 'Webp'); ?></label>            
            </div>
  
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('signin', '1', true); ?> <?php echo language::translate('title_signin', 'Signin'); ?></label>
            </div>  

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('disable_sign_in', '1', true); ?> <?php echo language::translate('title_disable_sign_in', 'Disable Sign In'); ?></label>
            </div>  

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('pending_guest', '1', true); ?> <?php echo language::translate('title_pending_guest', 'Pending Guest'); ?></label>            
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('hide_product', '1', true); ?> <?php echo language::translate('title_hide_product', 'Hide Product'); ?></label>
            </div>

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('hidden', '1', true); ?> <?php echo language::translate('title_hidden', 'Hidden'); ?></label>            
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('forbidden', '1', true); ?> <?php echo language::translate('title_forbidden', 'Forbidden'); ?></label>
            </div>

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('insanedeal', '1', true); ?> <?php echo language::translate('title_insane_deal', 'Insane Deal'); ?></label>             
            </div>
          </div>

  <div class="column" style="width: 165px;"> 

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('preorderable', '1', true); ?> <?php echo language::translate('title_preorderable', 'Pre-order'); ?></label>
            </div>

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('pending', '1', true); ?> <?php echo language::translate('title_pending', 'Pending'); ?></label>            
            </div>
  
            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('restock', '1', true); ?> <?php echo language::translate('title_restock', 'restock'); ?></label>
            </div>
            
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('backorder', '1', true); ?> <?php echo language::translate('title_backorder', 'Backorder'); ?></label>
            </div>

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('preowned', '1', true); ?> <?php echo language::translate('title_pre_-_owned', 'Pre-owned'); ?></label>
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('newarrival', '1', true); ?> <?php echo language::translate('title_newarrival', 'newarrival'); ?></label>
            </div>

            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('addtocart', '1', true); ?> <?php echo language::translate('title_addtocart', 'Add To Cart'); ?></label>
            </div>
             
            <div class="checkbox">
             <label><?php echo functions::form_draw_checkbox('specialoffer', '1', true); ?> <?php echo language::translate('title_specialoffer', 'Special Offer'); ?></label>
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('prebackorder', '1', true); ?> <?php echo language::translate('title_prebackorder', 'Pre-Backorder'); ?></label>
            </div>
          </div>
          
</div>
</br>

      
              
      



























































              <div class="form-group">
                <label><?php echo language::translate('title_date_valid_from', 'Date Valid From'); ?></label>
                <?php echo functions::form_draw_date_field('date_valid_from', true); ?>
              </div>


      <div class="form-group">
        <label><?php echo language::translate('title_date_valid_from_closing', 'Date Valid From Closing'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'date_valid_from_closing['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>

      
              <div class="form-group">
                <label><?php echo language::translate('title_date_valid_to', 'Date Valid To'); ?></label>
                <?php echo functions::form_draw_date_field('date_valid_to', true); ?>
              </div>


      <div class="form-group">
        <label><?php echo language::translate('title_date_valid_to_closing', 'Date Valid To Closing'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'date_valid_to_closing['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>
      
              <?php if (!empty($product->data['id'])) { ?>
              <div class="row">
                <div class="form-group col-md-6">
                  <label><?php echo language::translate('title_date_updated', 'Date Updated'); ?></label>
                  <div><?php echo language::strftime('%e %b %Y %H:%M', strtotime($product->data['date_updated'])); ?></div>
                </div>

                <div class="form-group col-md-6">
                  <label><?php echo language::translate('title_date_created', 'Date Created'); ?></label>
                  <div><?php echo language::strftime('%e %b %Y %H:%M', strtotime($product->data['date_created'])); ?></div>
                </div>
              </div>
              <?php } ?>
            </div>

            <div class="col-md-4">

              <div class="form-group">
                
      <label><?php echo language::translate('title_name', 'Name'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'name['. $language_code .']', true, 'style="height: 96px;"'); ?>
      </div>

      <div class="form-group">
        <label><?php echo language::translate('title_short_description', 'Short Description'); ?></label>
        <?php echo functions::form_draw_regional_textarea($language_code, 'short_description['. $language_code .']', true, 'style="height: 64px;"'); ?>
      </div>
      



      <div class="form-group">
        <label><?php echo language::translate('title_listing_info', 'Listing Info'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'listing_info['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>
      
              <div class="form-group">
                <label><?php echo language::translate('title_code', 'Code'); ?></label>
                <?php echo functions::form_draw_text_field('code', true); ?>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <label class="input-group-addon" style="width: 100px;"><?php echo language::translate('title_sku', 'SKU'); ?> <a href="https://en.wikipedia.org/wiki/Stock_keeping_unit" target="_blank"><?php echo functions::draw_fonticon('fa-external-link'); ?></a></label>
                  <?php echo functions::form_draw_text_field('sku', true); ?>
                </div>

                <div class="input-group">
                  <label class="input-group-addon" style="width: 100px;"><?php echo language::translate('title_mpn', 'MPN'); ?> <a href="https://en.wikipedia.org/wiki/Manufacturer_part_number" target="_blank"><?php echo functions::draw_fonticon('fa-external-link'); ?></a></label>
                  <?php echo functions::form_draw_text_field('mpn', true); ?>
                </div>

                <div class="input-group">
                  <label class="input-group-addon" style="width: 100px;"><?php echo language::translate('title_gtin', 'GTIN'); ?> <a href="https://en.wikipedia.org/wiki/Global_Trade_Item_Number" target="_blank"><?php echo functions::draw_fonticon('fa-external-link'); ?></a></label>
                  <?php echo functions::form_draw_text_field('gtin', true); ?>
                </div>

                <div class="input-group">
                  <label class="input-group-addon" style="width: 100px;"><?php echo language::translate('title_taric', 'TARIC'); ?> <a href="https://en.wikipedia.org/wiki/TARIC_code" target="_blank"><?php echo functions::draw_fonticon('fa-external-link'); ?></a></label>
                  <?php echo functions::form_draw_text_field('taric', true); ?>
                </div>
              </div>

              <div class="form-group">
                <label><?php echo language::translate('title_manufacturer', 'Manufacturer'); ?></label>
                <?php echo functions::form_draw_manufacturers_list('manufacturer_id', true); ?>
              </div>

              <div class="form-group">
                <label><?php echo language::translate('title_supplier', 'Supplier'); ?></label>
                <?php echo functions::form_draw_suppliers_list('supplier_id', true); ?>
              </div>

              <div class="form-group">
                <label><?php echo language::translate('title_keywords', 'Keywords'); ?></label>
                <?php echo functions::form_draw_text_field('keywords', true); ?>
              </div>

              <div class="form-group">
                <label><?php echo language::translate('title_listing_link', 'Listing Link'); ?></label>
                <?php echo functions::form_draw_listing_links_list('listing_link_id', true); ?>
              </div>   


          <div class="row">
            <div class="form-group col-md-6">
              <label><?php echo language::translate('title_views', 'Views'); ?></label>
              <div class="checkbox"><?php echo (int)$product->data['views']; ?></div>
            </div>

            <div class="form-group col-md-6">
              <label><?php echo language::translate('title_purchases', 'Purchases'); ?></label>
              <div class="checkbox"><?php echo (int)$product->data['purchases']; ?></div>
            </div>
          </div>
      
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo language::translate('title_images', 'Images'); ?></label>
                <div class="thumbnail">
<?php
  if (isset($product->data['id']) && !empty($product->data['images'])) {
    $image = current($product->data['images']);
    echo '<img class="main-image" src="'. document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $image['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping'))) .'" alt="" />';
    reset($product->data['images']);
  } else {
    echo '<img class="main-image" src="'. document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))) .'" alt="" />';
  }
?>
                </div>
              </div>

              <div id="images">

                <div class="images">
                  <?php if (!empty($_POST['images'])) foreach (array_keys($_POST['images']) as $key) { ?>
                  <div class="image form-group">
                    <?php echo functions::form_draw_hidden_field('images['.$key.'][id]', true); ?>
                    <?php echo functions::form_draw_hidden_field('images['.$key.'][filename]', $_POST['images'][$key]['filename']); ?>

                    <div class="thumbnail pull-left">
                      <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $product->data['images'][$key]['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />
                    </div>

                    <div class="input-group">
                      <?php echo functions::form_draw_text_field('images['.$key.'][new_filename]', isset($_POST['images'][$key]['new_filename']) ? $_POST['images'][$key]['new_filename'] : $_POST['images'][$key]['filename']); ?>
                      <div class="input-group-addon">
                        <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>
                        <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>
                        <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </div>

                <div class="new-images">
                  <div class="image form-group">
                    <div class="thumbnail pull-left">
                      <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />
                    </div>

                    <div class="input-group">
                      <?php echo functions::form_draw_file_field('new_images[]'); ?>
                      <div class="input-group-addon">
                        <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>
                        <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>
                        <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  
			
       </div>
      <a href="#" class="add" title="<?php echo language::translate('text_add', 'Add'); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?><a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #cc3333;"'); ?></a>
       <div>
       </br>
   <div class="pull-right">	<?php
		echo functions::form_draw_button('save_stay', language::translate('title_save_stay', 'Save & Stay'), 'submit', '', 'save');
		echo PHP_EOL;

		echo functions::form_draw_button('save', language::translate('title_save_exit', 'Save & Exit'), 'submit', '', 'save');
		echo PHP_EOL;
     ?>
   </div>
          
       </br>
   
    <script>
    
    $('#images').on('click', '.delete', function(e) {
        e.preventDefault();
        $('#images .form-group').remove();
        refreshMainImage();
    }); 
    

      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
    </script>
      
                </div>
              </div>
            </div>
          </div>

        </div>

        

        <div id="tab-categories" class="form-control" sstyle="overflow-y: auto; max-width: 3960px; max-height: 600px;">
        
              <div class="form-group">
                <label><?php echo language::translate('title_default_category', 'Default Category'); ?></label>
                <?php echo functions::form_draw_select_field('default_category_id', array(), true); ?>
              </div>        
        
              <div class="form-group">
                <label><?php echo language::translate('title_categories', 'Categories'); ?></label>
                <div class="form-control" style="overflow-y: auto; max-width: 3960px; max-height: 600px;">
<?php
  function custom_catalog_tree($category_id=0, $depth=1, $count=0) {

    $output = '';

    if ($category_id == 0) {
      $output .= '<div class="checkbox" id="category-id-'. $category_id .'"><label>'. functions::form_draw_checkbox('categories[]', '0', (isset($_POST['categories']) && in_array('0', $_POST['categories'], true)) ? '0' : false, 'data-name="'. htmlspecialchars(language::translate('title_root', 'Root')) .'" data-priority="0"') .' '. functions::draw_fonticon('fa-folder', 'title="'. language::translate('title_root', 'Root') .'" style="color: #cccc66;"') .' ['. language::translate('title_root', 'Root') .']</label></div>' . PHP_EOL;
    }

  // Output categories
    $categories_query = database::query(
      "select c.id, ci.name
      from ". DB_TABLE_CATEGORIES ." c
      left join ". DB_TABLE_CATEGORIES_INFO ." ci on (ci.category_id = c.id and ci.language_code = '". language::$selected['code'] ."')
      where c.parent_id = ". (int)$category_id ."
      order by c.priority asc, ci.name asc;"
    );

    while ($category = database::fetch($categories_query)) {
      $count++;

      $output .= '  <div class="checkbox"><label>'. functions::form_draw_checkbox('categories[]', $category['id'], true, 'data-name="'. htmlspecialchars($category['name']) .'" data-priority="'. $count .'"') .' '. functions::draw_fonticon('fa-folder fa-lg', 'style="color: #cccc66; margin-left: '. ($depth*1) .'em;"') .' '. $category['name'] .'</label></div>' . PHP_EOL;

      if (database::num_rows(database::query("select * from ". DB_TABLE_CATEGORIES ." where parent_id = ". (int)$category['id'] ." limit 1;")) > 0) {
        $output .= custom_catalog_tree($category['id'], $depth+1, $count);
      }
    }

    database::free($categories_query);

    return $output;
  }

  echo custom_catalog_tree();
?>
                </div>
              </div>
    </div>
              
      
      <div id="tab-information" class="tab-pane" style="max-width: 1280px;">
      

          <ul class="nav nav-tabs">
            <?php foreach (language::$languages as $language) { ?>
              <li<?php echo ($language['code'] == language::$selected['code']) ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#<?php echo $language['code']; ?>"><?php echo $language['name']; ?></a></li>
            <?php } ?>
          </ul>

          <div class="tab-content">
            <?php foreach (array_keys(language::$languages) as $language_code) { ?>
            <div id="<?php echo $language_code; ?>" class="tab-pane fade in<?php echo ($language_code == language::$selected['code']) ? ' active' : ''; ?>">

              <div class="form-group">
                
      </div>
      



      <div class="form-group">
        <label><?php echo language::translate('title_medium_description', 'Medium Description'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'medium_description['. $language_code .']', true, 'style="height: 120px;"'); ?>
      </div>
      
 

       <div class="form-group">
        <label><?php echo language::translate('title_box_conditions', 'Condition:'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'box_conditions['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>
      
      
              <div class="form-group">
                <label><?php echo language::translate('title_description', 'Description'); ?></label>
                <?php echo functions::form_draw_regional_wysiwyg_field($language_code, 'description['. $language_code .']', true, 'style="height: 250px; color: #fff"'); ?>
              </div>

              <div class="form-group">
                <label><?php echo language::translate('title_technical_data', 'Technical Data'); ?> <a class="technical-data-hint" href="#"><?php echo functions::draw_fonticon('fa-question-circle'); ?></a></label>
                <?php echo functions::form_draw_regional_textarea($language_code, 'technical_data['. $language_code .']', true, 'style="height: 250px;"'); ?>
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label><?php echo language::translate('title_head_title', 'Head Title'); ?></label>
                  <?php echo functions::form_draw_regional_input_field($language_code, 'head_title['. $language_code .']', true); ?>
                </div>

                <div class="form-group col-md-6">
                  <label><?php echo language::translate('title_meta_description', 'Meta Description'); ?></label>
                  <?php echo functions::form_draw_regional_input_field($language_code, 'meta_description['. $language_code .']', true); ?>
                </div>
              </div>

            </div>
            <?php } ?>

          </div>
        </div>

        <div id="tab-attributes" class="tab-pane" style="max-width: 960px;">

          <table class="table table-striped data-table">
            <thead>
              <tr>
                <th style="width: 320px;"><?php echo language::translate('title_group', 'Group'); ?></th>
                <th style="width: 320px;"><?php echo language::translate('title_value', 'Value'); ?></th>
                <th><?php echo language::translate('title_custom_value', 'Custom Value'); ?></th>
                <th style="width: 60px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($_POST['attributes'])) foreach (array_keys($_POST['attributes']) as $key) { ?>
              <tr>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][id]', true); ?>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][group_id]', true); ?>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][group_name]', true); ?>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][value_id]', true); ?>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][value_name]', true); ?>
                <?php echo functions::form_draw_hidden_field('attributes['.$key.'][custom_value]', true); ?>
                <td><?php echo $_POST['attributes'][$key]['group_name']; ?></td>
                <td><?php echo $_POST['attributes'][$key]['value_name']; ?></td>
                <td><?php echo $_POST['attributes'][$key]['custom_value']; ?></td>
                <td class="text-right"><a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a></td>
              </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td><?php echo functions::form_draw_attribute_groups_list('new_attribute[group_id]', array(), ''); ?></td>
                <td><?php echo functions::form_draw_select_field('new_attribute[value_id]', array(), ''); ?></td>
                <td><?php echo functions::form_draw_text_field('new_attribute[custom_value]', ''); ?></td>
                <td><?php echo functions::form_draw_button('add', language::translate('title_add', 'Add'), 'button'); ?></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div id="tab-prices" class="tab-pane">

          <div id="prices" style="max-width: 640px;">

      <div class="form-group">
        <label><?php echo language::translate('title_costing_information', 'Costing Information'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'costing_information['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>
      
      <div class="form-group">
        <label><?php echo language::translate('title_guess_price', 'Guess Price'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'guess_price['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>      
      
            <h2><?php echo language::translate('title_prices', 'Prices'); ?></h2>

            <div class="row">
              <div class="form-group col-md-6">
                <label><?php echo language::translate('title_purchase_price', 'Purchase Price'); ?></label>
                <div class="input-group">
                  <?php echo functions::form_draw_decimal_field('purchase_price', true, 2, 0, null); ?>
                  <span class="input-group-addon">
                    <?php echo functions::form_draw_currencies_list('purchase_price_currency_code', true, false); ?>
                  </span>
                </div>
              </div>

              <div class="form-group col-md-6">
                <label><?php echo language::translate('title_tax_class', 'Tax Class'); ?></label>
                <?php echo functions::form_draw_tax_classes_list('tax_class_id', true); ?>
              </div>
            </div>

            <table class="table table-striped data-table">
              <thead>
                <tr>
                  <td style="width: 50%;"><?php echo language::translate('title_price', 'Price'); ?></td>
                  <td style="width: 50%;"><?php echo language::translate('title_price_incl_tax', 'Price Incl. Tax'); ?> (<a id="price-incl-tax-tooltip" href="#">?</a>)</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'prices['. settings::get('store_currency_code') .']', true, 'data-currency-price="" placeholder=""'); ?></td>
                <td><?php echo functions::form_draw_decimal_field('gross_prices['. settings::get('store_currency_code') .']', '', currency::$currencies[settings::get('store_currency_code')]['decimals'], 0, null, 'placeholder=""'); ?></td>
                </tr>
<?php
  foreach (currency::$currencies as $currency) {
    if ($currency['code'] == settings::get('store_currency_code')) continue;
?>
                <tr>
                  <td><?php echo functions::form_draw_currency_field($currency['code'], 'prices['. $currency['code'] .']', true, 'data-currency-price="" placeholder=""'); ?></td>
                <td><?php echo functions::form_draw_decimal_field('gross_prices['. $currency['code'] .']', '', $currency['decimals'], 0, null, 'placeholder=""'); ?></td>
                </tr>
<?php
  }
?>
              </tbody>
            </table>
          </div>

          


<style>
#customer-group-prices td:not(:last-child){
  width: 320px;
}
</style>


	                     
   <style>
            #sign-in-date-price-prices td:not(:last-child){
                width: 260px;
            }
        </style>
        <h2><?php echo language::translate('title_sign_in_date_price_prices', 'Sign In Date'); ?></h2>

        <table id="sign-in-date-price-prices" class="table table-striped data-table">
            <thead>
            <tr>
                <td colspan="5">
                <a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
                <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
                <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
                </td>
            </tr>            
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_sign_in_date_price', 'Guest Price'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>

                <th></th>
            </tr>            
            </thead>
            <tbody>
            <?php if (!empty($_POST['sign_in_date_price_prices'])) foreach (array_keys($_POST['sign_in_date_price_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>

                    <td><?php echo functions::form_draw_hidden_field('sign_in_date_price_prices['.$key.'][id]', true) . functions::form_draw_sign_in_date_prices_list('sign_in_date_price_prices['.$key.'][sign_in_date_price_id]', true); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('sign_in_date_price_prices['.$key.'][start_date]', true, '','sign_in_date_price'); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('sign_in_date_price_prices['.$key.'][end_date]', true, '','sign_in_date_price'); ?></td>
                    <td>

                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
<script>

$('body').on('keyup change', 'input[name="reset_sign_in_date_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
    $('input[name^="sign_in_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_sign_in_date_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
    });
});

  $('body').on('keyup change', 'input[name^="sign_in_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);
console.log(percentage);
    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="sign_in_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="sign_in_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="sign_in_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="sign_in_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="sign_in_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

$('#sign-in-date-price-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});
$('#sign-in-date-price-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#sign-in-date-price-prices tbody tr').remove();
});
$('#sign-in-date-price-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#sign-in-date-price-prices tbody tr').toggle();
});
// $('#sign-in-date-price-prices tbody tr').toggle();

var new_sign_in_date_price_price_i = 1;
$('#sign-in-date-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('sign_in_date_price_prices[new_sign_in_date_price_price_i][id]', '') . functions::form_draw_sign_in_date_prices_list('sign_in_date_price_prices[new_sign_in_date_price_price_i][sign_in_date_price_id]', '')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('sign_in_date_price_prices[new_sign_in_date_price_price_i][start_date]', true, '', 'sign_in_date_price')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('sign_in_date_price_prices[new_sign_in_date_price_price_i][end_date]', true, '' , 'sign_in_date_price')); ?></td>'
+ '</tr>';
while ($('input[name="sign_in_date_price_prices[new_'+new_sign_in_date_price_price_i+']"]').length) new_sign_in_date_price_price_i++;
output = output.replace(/new_sign_in_date_price_price_i/g, 'new_' + new_sign_in_date_price_price_i);
$('#sign-in-date-price-prices tbody').append(output);
new_sign_in_date_price_price_i++;
});


      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

                        
      <h2><?php echo language::translate('title_stock_guest_prices', 'Stock Guest Prices'); ?></h2>
        <table id="stock-quantity-guest-prices" class="table table-striped">
            <thead>
            <tr>
               <td colspan="5">
               <a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
               <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
               <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
               </td>
            </tr>
            
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>

                <th style="width: 260px;"><?php echo language::translate('title_stock_guest_prices', 'Stock Guest Prices'); ?></th>
                <th colspan="<?php echo count(currency::$currencies); ?>"><?php echo language::translate('title_price', 'Price'); ?></th>
                <th></th>
            </tr>            
           
            </thead>
            <tbody>
            <?php if (!empty($_POST['stock_quantity_guest_prices'])) foreach (array_keys($_POST['stock_quantity_guest_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                
                    <td><?php echo functions::form_draw_hidden_field('stock_quantity_guest_prices['.$key.'][id]', true) . functions::form_draw_decimal_field('stock_quantity_guest_prices['.$key.'][stock_quantity]', true, 2); ?></td>
                    <td style="width: 250px;"><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_guest_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td>
                    <?php
                    foreach (array_keys(currency::$currencies) as $currency_code) {
                        if ($currency_code == settings::get('store_currency_code')) continue;
                        ?>
<!--
                        <td style="width: 250px;"><?php echo functions::form_draw_currency_field($currency_code, 'stock_quantity_guest_prices['.$key.']['. $currency_code. ']', isset($_POST['stock_quantity_guest_prices'][$key][$currency_code]) ? number_format($_POST['stock_quantity_guest_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
-->
                        <?php
                    }
                    ?>
                </tr>
            <?php } ?>            
            
            </tbody>
            <tfoot>
            </tfoot>
            
        </table>
<script>
  $('body').on('keyup change', 'input[name^="stock_quantity_guest_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="stock_quantity_guest_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="stock_quantity_guest_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="stock_quantity_guest_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="stock_quantity_guest_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="stock_quantity_guest_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

var new_stock_quantity_guest_price_i = 1;
$('#stock-quantity-guest-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_guest_prices[new_'+new_stock_quantity_guest_price_i+']"]').length) new_stock_quantity_guest_price_i++;
    output = output.replace(/new_stock_quantity_guest_price_i/g, 'new_' + new_stock_quantity_guest_price_i);
    $('#stock-quantity-guest-prices tbody').append(output);
    new_stock_quantity_guest_price_i++;
});

var new_stock_quantity_guest_price_i = 2;
$('#stock-quantity-guest-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + ' <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
    
        + ' <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_guest_prices[new_'+new_stock_quantity_guest_price_i+']"]').length) new_stock_quantity_guest_price_i++;
    output = output.replace(/new_stock_quantity_guest_price_i/g, 'new_' + new_stock_quantity_guest_price_i);
    $('#stock-quantity-guest-prices tbody').append(output);
    new_stock_quantity_guest_price_i++;
});

var new_stock_quantity_guest_price_i = 3;
$('#stock-quantity-guest-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
    
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_guest_prices[new_'+new_stock_quantity_guest_price_i+']"]').length) new_stock_quantity_guest_price_i++;
    output = output.replace(/new_stock_quantity_guest_price_i/g, 'new_' + new_stock_quantity_guest_price_i);
    $('#stock-quantity-guest-prices tbody').append(output);
    new_stock_quantity_guest_price_i++;
});

var new_stock_quantity_guest_price_i = 4;
$('#stock-quantity-guest-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
    
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_guest_prices[new_stock_quantity_guest_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_guest_prices[new_stock_quantity_guest_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_guest_prices[new_'+new_stock_quantity_guest_price_i+']"]').length) new_stock_quantity_guest_price_i++;
    output = output.replace(/new_stock_quantity_guest_price_i/g, 'new_' + new_stock_quantity_guest_price_i);
    $('#stock-quantity-guest-prices tbody').append(output);
    new_stock_quantity_guest_price_i++;
});

$('#stock-quantity-guest-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

$('#stock-quantity-guest-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#stock-quantity-guest-prices tbody tr').remove();
});

$('#stock-quantity-guest-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#stock-quantity-guest-prices tbody tr').toggle();
});
// $('#stock-quantity-guest-prices tbody tr').toggle();

      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

      <h2><?php echo language::translate('title_stock_prices', 'Stock Prices'); ?></h2>
        <table id="stock-quantity-prices" class="table table-striped">
            <thead>
            <tr>
               <td colspan="5"><a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
               <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
               <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
               </td>
            </tr>            

            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_stock_quantity', 'Stock Quantity'); ?></th>
                <th colspan="<?php echo count(currency::$currencies); ?>"><?php echo language::translate('title_price', 'Price'); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($_POST['stock_quantity_prices'])) foreach (array_keys($_POST['stock_quantity_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                    <td><?php echo functions::form_draw_hidden_field('stock_quantity_prices['.$key.'][id]', true) . functions::form_draw_decimal_field('stock_quantity_prices['.$key.'][stock_quantity]', true, 2); ?></td>
                    <td style="width: 250px;"><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td>
                    <?php
                    foreach (array_keys(currency::$currencies) as $currency_code) {
                        if ($currency_code == settings::get('store_currency_code')) continue;
                        ?>
<!--
                        <td style="width: 250px;"><?php echo functions::form_draw_currency_field($currency_code, 'stock_quantity_prices['.$key.']['. $currency_code. ']', isset($_POST['stock_quantity_prices'][$key][$currency_code]) ? number_format($_POST['stock_quantity_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
-->
                        <?php
                    }
                    ?>
                </tr>
            <?php } ?>
            </tbody>
            
            <tfoot>
            </tfoot>
        </table>
<script>
  $('body').on('keyup change', 'input[name^="stock_quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="stock_quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="stock_quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="stock_quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="stock_quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="stock_quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

var new_stock_quantity_price_i = 1;
$('#stock-quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_prices[new_stock_quantity_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_prices[new_stock_quantity_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_prices[new_stock_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_prices[new_stock_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_prices[new_'+new_stock_quantity_price_i+']"]').length) new_stock_quantity_price_i++;
    output = output.replace(/new_stock_quantity_price_i/g, 'new_' + new_stock_quantity_price_i);
    $('#stock-quantity-prices tbody').append(output);
    new_stock_quantity_price_i++;
});

var new_stock_quantity_price_i = 2;
$('#stock-quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_prices[new_stock_quantity_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_prices[new_stock_quantity_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_prices[new_stock_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_prices[new_stock_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_prices[new_'+new_stock_quantity_price_i+']"]').length) new_stock_quantity_price_i++;
    output = output.replace(/new_stock_quantity_price_i/g, 'new_' + new_stock_quantity_price_i);
    $('#stock-quantity-prices tbody').append(output);
    new_stock_quantity_price_i++;
});

var new_stock_quantity_price_i = 3;
$('#stock-quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_prices[new_stock_quantity_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_prices[new_stock_quantity_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_prices[new_stock_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_prices[new_stock_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_prices[new_'+new_stock_quantity_price_i+']"]').length) new_stock_quantity_price_i++;
    output = output.replace(/new_stock_quantity_price_i/g, 'new_' + new_stock_quantity_price_i);
    $('#stock-quantity-prices tbody').append(output);
    new_stock_quantity_price_i++;
});

var new_stock_quantity_price_i = 4;
$('#stock-quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
        + '  <td style="padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
        + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('stock_quantity_prices[new_stock_quantity_price_i][id]', '') . functions::form_draw_decimal_field('stock_quantity_prices[new_stock_quantity_price_i][stock_quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'stock_quantity_prices[new_stock_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
        <?php
        foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
        ?>
//        + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'stock_quantity_prices[new_stock_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
        <?php
        }
        ?>
        + '</tr>';
    while ($('input[name="stock_quantity_prices[new_'+new_stock_quantity_price_i+']"]').length) new_stock_quantity_price_i++;
    output = output.replace(/new_stock_quantity_price_i/g, 'new_' + new_stock_quantity_price_i);
    $('#stock-quantity-prices tbody').append(output);
    new_stock_quantity_price_i++;
});

$('#stock-quantity-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

$('#stock-quantity-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#stock-quantity-prices tbody tr').remove();
});

$('#stock-quantity-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#stock-quantity-prices tbody tr').toggle();
});
// $('#stock-quantity-prices tbody tr').toggle();

      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

      <style>
            #default-price-prices td:not(:last-child){
                width: 260px;
            }
        </style>
        
      <style>


	          
            
<style>
            #guest-price-prices td:not(:last-child){
                width: 260px;
            }
        </style>
        
        <h2><?php echo language::translate('title_guest_price_prices', 'Guest Price Prices'); ?></h2>
       
        
        <table id="guest-price-prices" class="table table-striped data-table">
            <thead>
            <tr>
                <td colspan="5">
                <a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
                <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
                <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
                <a class="delete_all" style="margin-left: 80px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a>
                </td>
            </tr>                       
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_guest_price', 'Default Price'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
                <th style="width: 260px;"><?php echo settings::get('store_currency_code'); ?></th>
               <!-- <?php foreach (array_keys(currency::$currencies) as $currency_code) {
                    if ($currency_code == settings::get('store_currency_code')) continue;
                    ?>
                    <th style="width: 260px;"><?php echo $currency_code; ?></th>
                <?php } ?> -->
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($_POST['guest_price_prices'])) foreach (array_keys($_POST['guest_price_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                    <td><?php echo functions::form_draw_hidden_field('guest_price_prices['.$key.'][id]', true) . functions::form_draw_guest_prices_list('guest_price_prices['.$key.'][guest_price_id]', true); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('guest_price_prices['.$key.'][start_date]', true, '','guest_price'); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('guest_price_prices['.$key.'][end_date]', true, '','guest_price'); ?></td>
                    <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'guest_price_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td><?php foreach (array_keys(currency::$currencies) as $currency_code) { if ($currency_code == settings::get('store_currency_code')) continue;?>
                    <td style="display: none;"><?php echo functions::form_draw_currency_field($currency_code, 'guest_price_prices['.$key.']['. $currency_code. ']', isset($_POST['guest_price_prices'][$key][$currency_code]) ? number_format($_POST['guest_price_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            
            </tfoot>
        </table>
<script>

$('body').on('keyup change', 'input[name="reset_guest_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
    $('input[name^="guest_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_guest_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
    });
});

  $('body').on('keyup change', 'input[name^="guest_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);
console.log(percentage);
    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="guest_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="guest_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="guest_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="guest_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="guest_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

$('#guest-price-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

$('#guest-price-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#guest-price-prices tbody input[name^="guest_price_prices"]').val('');
    $('#guest-price-prices tbody select[name^="guest_price_prices"]').prop('selectedIndex', 0).selectmenu('refresh', true);
});


$('#guest-price-prices').on('click', '.delete_all', function(e) {
    e.preventDefault();
    $('#guest-price-prices tbody tr').remove();
});


$('#guest-price-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#guest-price-prices tbody tr').toggle();
});

<!-- $('#guest-price-prices tbody tr').toggle(); -->

var new_guest_price_price_i = 1;
$('#guest-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('guest_price_prices[new_guest_price_price_i][id]', '') . functions::form_draw_guest_prices_list('guest_price_prices[new_guest_price_price_i][guest_price_id]', '1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][start_date]', true, '', 'guest_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][end_date]', true, '' , 'guest_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'guest_price_prices[new_guest_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'guest_price_prices[new_guest_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="guest_price_prices[new_'+new_guest_price_price_i+']"]').length) new_guest_price_price_i++;
output = output.replace(/new_guest_price_price_i/g, 'new_' + new_guest_price_price_i);
$('#guest-price-prices tbody').append(output);
new_guest_price_price_i++;
});

var new_guest_price_price_i = 2;
$('#guest-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('guest_price_prices[new_guest_price_price_i][id]', '') . functions::form_draw_guest_prices_list('guest_price_prices[new_guest_price_price_i][guest_price_id]', '2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][start_date]', true, '', 'guest_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][end_date]', true, '' , 'guest_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'guest_price_prices[new_guest_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'guest_price_prices[new_guest_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="guest_price_prices[new_'+new_guest_price_price_i+']"]').length) new_guest_price_price_i++;
output = output.replace(/new_guest_price_price_i/g, 'new_' + new_guest_price_price_i);
$('#guest-price-prices tbody').append(output);
new_guest_price_price_i++;
});

var new_guest_price_price_i = 3;
$('#guest-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('guest_price_prices[new_guest_price_price_i][id]', '') . functions::form_draw_guest_prices_list('guest_price_prices[new_guest_price_price_i][guest_price_id]', '3')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][start_date]', true, '', 'guest_price_3')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('guest_price_prices[new_guest_price_price_i][end_date]', true, '' , 'guest_price_3')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'guest_price_prices[new_guest_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'guest_price_prices[new_guest_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="guest_price_prices[new_'+new_guest_price_price_i+']"]').length) new_guest_price_price_i++;
output = output.replace(/new_guest_price_price_i/g, 'new_' + new_guest_price_price_i);
$('#guest-price-prices tbody').append(output);
new_guest_price_price_i++;
});


      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      
        <h2><?php echo language::translate('title_default_price_prices', 'Default Price Prices'); ?></h2>

        <table id="default-price-prices" class="table table-striped data-table">
            <thead>
            <tr>
                <td colspan="5">
                <a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
                <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
                <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
                <a class="delete_all" style="margin-left: 80px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a>
                </td>
            </tr>                       
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_default_price', 'Default Price'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
                <th style="width: 260px;"><?php echo settings::get('store_currency_code'); ?></th>
               <!-- <?php foreach (array_keys(currency::$currencies) as $currency_code) {
                    if ($currency_code == settings::get('store_currency_code')) continue;
                    ?>
                    <th style="width: 260px;"><?php echo $currency_code; ?></th>
                <?php } ?> -->
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($_POST['default_price_prices'])) foreach (array_keys($_POST['default_price_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                    <td><?php echo functions::form_draw_hidden_field('default_price_prices['.$key.'][id]', true) . functions::form_draw_default_prices_list('default_price_prices['.$key.'][default_price_id]', true); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('default_price_prices['.$key.'][start_date]', true, '','default_price'); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('default_price_prices['.$key.'][end_date]', true, '','default_price'); ?></td>
                    <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'default_price_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td><?php foreach (array_keys(currency::$currencies) as $currency_code) { if ($currency_code == settings::get('store_currency_code')) continue;?>
                    <td style="display: none;"><?php echo functions::form_draw_currency_field($currency_code, 'default_price_prices['.$key.']['. $currency_code. ']', isset($_POST['default_price_prices'][$key][$currency_code]) ? number_format($_POST['default_price_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            
            </tfoot>
        </table>
<script>
$('body').on('keyup change', 'input[name="reset_default_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
    $('input[name^="default_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_default_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
    });
});


  $('body').on('keyup change', 'input[name^="default_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);
console.log(percentage);
    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="default_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="default_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="default_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="default_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="default_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

$('#default-price-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

  $('#default-price-prices').on('click', '.delete_all', function(e) {
    e.preventDefault();
    $('#default-price-prices tbody tr').remove();
  });

$('#default-price-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#default-price-prices tbody input[name^="default_price_prices"]').val('');
    $('#default-price-prices tbody select[name^="default_price_prices"]').prop('selectedIndex', 0).selectmenu('refresh', true);
});

$('#default-price-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#default-price-prices tbody tr').toggle();
});

<!--  $('#default-price-prices tbody tr').toggle(); -->

var new_default_price_price_i = 1;
$('#default-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px;  padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('default_price_prices[new_default_price_price_i][id]', '') . functions::form_draw_default_prices_list('default_price_prices[new_default_price_price_i][default_price_id]', '1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('default_price_prices[new_default_price_price_i][start_date]', true, '', 'default_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('default_price_prices[new_default_price_price_i][end_date]', true, '' , 'default_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'default_price_prices[new_default_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'default_price_prices[new_default_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="default_price_prices[new_'+new_default_price_price_i+']"]').length) new_default_price_price_i++;
output = output.replace(/new_default_price_price_i/g, 'new_' + new_default_price_price_i);
$('#default-price-prices tbody').append(output);
new_default_price_price_i++;
});

var new_default_price_price_i = 2;
$('#default-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px;  padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('default_price_prices[new_default_price_price_i][id]', '') . functions::form_draw_default_prices_list('default_price_prices[new_default_price_price_i][default_price_id]', '2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('default_price_prices[new_default_price_price_i][start_date]', true, '', 'default_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('default_price_prices[new_default_price_price_i][end_date]', true, '' , 'default_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'default_price_prices[new_default_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'default_price_prices[new_default_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="default_price_prices[new_'+new_default_price_price_i+']"]').length) new_default_price_price_i++;
output = output.replace(/new_default_price_price_i/g, 'new_' + new_default_price_price_i);
$('#default-price-prices tbody').append(output);
new_default_price_price_i++;
});



      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

     <style>
            #wholesale-price-prices td:not(:last-child){
                width: 260px;
            }
        </style>
        <h2><?php echo language::translate('title_wholesale_price_prices', 'Wholesale Price Prices'); ?></h2>
     
        <table id="wholesale-price-prices" class="table table-striped data-table">
            <thead>
            
            <tr>
                <td colspan="5"><a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
                <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
                <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
                <a class="delete_all" style="margin-left: 80px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a>
                </td>
            </tr>
            
            </thead>
            <thead>
            
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_wholesale_price', 'Wholesale Price'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
                <th style="width: 260px;"><?php echo settings::get('store_currency_code'); ?></th>
            <!--    <?php foreach (array_keys(currency::$currencies) as $currency_code) {
                    if ($currency_code == settings::get('store_currency_code')) continue;
                    ?>
                    <th style="width: 260px;"><?php echo $currency_code; ?></th>
                <?php } ?>  -->
                <th></th>
            </tr>
            
            </thead>
            <tbody>
            
            <?php if (!empty($_POST['wholesale_price_prices'])) foreach (array_keys($_POST['wholesale_price_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                    <td><?php echo functions::form_draw_hidden_field('wholesale_price_prices['.$key.'][id]', true) . functions::form_draw_wholesale_prices_list('wholesale_price_prices['.$key.'][default_wholesale_price_id]', true); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('wholesale_price_prices['.$key.'][start_date]', true, '','wholesale_price'); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('wholesale_price_prices['.$key.'][end_date]', true, '','wholesale_price'); ?> </td>
                    <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'wholesale_price_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td>
                    <?php foreach (array_keys(currency::$currencies) as $currency_code) { if ($currency_code == settings::get('store_currency_code')) continue;?>
                    <td style="display: none;"><?php echo functions::form_draw_currency_field($currency_code, 'wholesale_price_prices['.$key.']['. $currency_code. ']', isset($_POST['wholesale_price_prices'][$key][$currency_code]) ? number_format($_POST['wholesale_price_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
              <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            
            </tfoot>
        </table>
<script>

$('body').on('keyup change', 'input[name="reset_wholesale_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
    $('input[name^="wholesale_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_wholesale_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
    });
});

  $('body').on('keyup change', 'input[name^="wholesale_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);
console.log(percentage);
    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="wholesale_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="wholesale_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="wholesale_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="wholesale_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="wholesale_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

$('#wholesale-price-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

$('#wholesale-price-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#wholesale-price-prices tbody input[name^="wholesale_price_prices"]').val('');
    $('#wholesale-price-prices tbody select[name^="wholesale_price_prices"]').prop('selectedIndex', 0).selectmenu('refresh', true);
});

$('#wholesale-price-prices').on('click', '.delete_all', function(e) {
    e.preventDefault();
    $('#wholesale-price-prices tbody tr').remove();
});

$('#wholesale-price-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#wholesale-price-prices tbody tr').toggle();
});

<!-- $('#wholesale-price-prices tbody tr').toggle(); -->


var new_wholesale_price_price_i = 1;
$('#wholesale-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('wholesale_price_prices[new_wholesale_price_price_i][id]', '') . functions::form_draw_wholesale_prices_list('wholesale_price_prices[new_wholesale_price_price_i][default_wholesale_price_id]', '1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('wholesale_price_prices[new_wholesale_price_price_i][start_date]', true, '', 'wholesale_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('wholesale_price_prices[new_wholesale_price_price_i][end_date]', true, '' , 'wholesale_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'wholesale_price_prices[new_wholesale_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'wholesale_price_prices[new_wholesale_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="wholesale_price_prices[new_'+new_wholesale_price_price_i+']"]').length) new_wholesale_price_price_i++;
output = output.replace(/new_wholesale_price_price_i/g, 'new_' + new_wholesale_price_price_i);
$('#wholesale-price-prices tbody').append(output);
new_wholesale_price_price_i++;
});

var new_wholesale_price_price_i = 2;
$('#wholesale-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('wholesale_price_prices[new_wholesale_price_price_i][id]', '') . functions::form_draw_wholesale_prices_list('wholesale_price_prices[new_wholesale_price_price_i][default_wholesale_price_id]', '2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('wholesale_price_prices[new_wholesale_price_price_i][start_date]', true, '', 'wholesale_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('wholesale_price_prices[new_wholesale_price_price_i][end_date]', true, '' , 'wholesale_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'wholesale_price_prices[new_wholesale_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'wholesale_price_prices[new_wholesale_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="wholesale_price_prices[new_'+new_wholesale_price_price_i+']"]').length) new_wholesale_price_price_i++;
output = output.replace(/new_wholesale_price_price_i/g, 'new_' + new_wholesale_price_price_i);
$('#wholesale-price-prices tbody').append(output);
new_wholesale_price_price_i++;
});


      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

	          
            
<style>
            #fake-sold-out-date-price-prices td:not(:last-child){
                width: 260px;
            }
        </style>
        <h2><?php echo language::translate('title_fake_sold_out_date_price_prices', 'Sold Out Date'); ?></h2>
        


        

        <table id="fake-sold-out-date-price-prices" class="table table-striped data-table">
            <thead>
            <tr>
                <td colspan="5">
                <a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
                <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
                <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
                <a class="delete_all" style="margin-left: 80px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a>
                </td>
            </tr>                       
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 260px;"><?php echo language::translate('title_fake_sold_out_date_price', 'Default Price'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 260px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
                <th style="width: 260px;"><?php echo settings::get('store_currency_code'); ?></th>
               <!-- <?php foreach (array_keys(currency::$currencies) as $currency_code) {
                    if ($currency_code == settings::get('store_currency_code')) continue;
                    ?>
                    <th style="width: 260px;"><?php echo $currency_code; ?></th>
                <?php } ?> -->
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($_POST['fake_sold_out_date_price_prices'])) foreach (array_keys($_POST['fake_sold_out_date_price_prices']) as $key) { ?>
                <tr>
                <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
                    <td><?php echo functions::form_draw_hidden_field('fake_sold_out_date_price_prices['.$key.'][id]', true) . functions::form_draw_fake_sold_out_date_prices_list('fake_sold_out_date_price_prices['.$key.'][fake_sold_out_date_price_id]', true); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('fake_sold_out_date_price_prices['.$key.'][start_date]', true, '','fake_sold_out_date_price'); ?></td>
                    <td><?php echo functions::form_draw_datetime_field('fake_sold_out_date_price_prices['.$key.'][end_date]', true, '','fake_sold_out_date_price'); ?></td>
                    <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'fake_sold_out_date_price_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td><?php foreach (array_keys(currency::$currencies) as $currency_code) { if ($currency_code == settings::get('store_currency_code')) continue;?>
                    <td style="display: none;"><?php echo functions::form_draw_currency_field($currency_code, 'fake_sold_out_date_price_prices['.$key.']['. $currency_code. ']', isset($_POST['fake_sold_out_date_price_prices'][$key][$currency_code]) ? number_format($_POST['fake_sold_out_date_price_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            
            </tfoot>
        </table>
<script>



$('body').on('keyup change', 'input[name="reset_fake_sold_out_date_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
    $('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_fake_sold_out_date_price_prices[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
    });
});

  $('body').on('keyup change', 'input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);
console.log(percentage);
    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
        $(parent).find('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
});
$('input[name^="fake_sold_out_date_price_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

$('#fake-sold-out-date-price-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});

$('#fake-sold-out-date-price-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#fake-sold-out-date-price-prices tbody input[name^="fake_sold_out_date_price_prices"]').val('');
    $('#fake-sold-out-date-price-prices tbody select[name^="fake_sold_out_date_price_prices"]').prop('selectedIndex', 0).selectmenu('refresh', true);
});


$('#fake-sold-out-date-price-prices').on('click', '.delete_all', function(e) {
    e.preventDefault();
    $('#fake-sold-out-date-price-prices tbody tr').remove();
});


$('#fake-sold-out-date-price-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#fake-sold-out-date-price-prices tbody tr').toggle();
});

<!-- $('#fake-sold-out-date-price-prices tbody tr').toggle(); -->

var new_fake_sold_out_date_price_price_i = 1;
$('#fake-sold-out-date-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ ' <td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][id]', '') . functions::form_draw_fake_sold_out_date_prices_list('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][fake_sold_out_date_price_id]', '1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][start_date]', true, '', 'fake_sold_out_date_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][end_date]', true, '' , 'fake_sold_out_date_price_1')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="fake_sold_out_date_price_prices[new_'+new_fake_sold_out_date_price_price_i+']"]').length) new_fake_sold_out_date_price_price_i++;
output = output.replace(/new_fake_sold_out_date_price_price_i/g, 'new_' + new_fake_sold_out_date_price_price_i);
$('#fake-sold-out-date-price-prices tbody').append(output);
new_fake_sold_out_date_price_price_i++;
});

var new_fake_sold_out_date_price_price_i = 2;
$('#fake-sold-out-date-price-prices').on('click', '.add', function(e) {
e.preventDefault();
var output = '<tr>'
+ '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
+ ' <td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][id]', '') . functions::form_draw_fake_sold_out_date_prices_list('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][fake_sold_out_date_price_id]', '2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][start_date]', true, '', 'fake_sold_out_date_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_datetime_field('fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i][end_date]', true, '' , 'fake_sold_out_date_price_2')); ?></td>'
+ '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i]['. settings::get("store_currency_code") .']', '', 'data-size="small"')); ?></td>'
<?php foreach (array_keys(currency::$currencies) as $currency_code) {
if ($currency_code == settings::get('store_currency_code')) continue;
?>
+ ' <td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'fake_sold_out_date_price_prices[new_fake_sold_out_date_price_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php } ?>
+ '</tr>';
while ($('input[name="fake_sold_out_date_price_prices[new_'+new_fake_sold_out_date_price_price_i+']"]').length) new_fake_sold_out_date_price_price_i++;
output = output.replace(/new_fake_sold_out_date_price_price_i/g, 'new_' + new_fake_sold_out_date_price_price_i);
$('#fake-sold-out-date-price-prices tbody').append(output);
new_fake_sold_out_date_price_price_i++;
});


      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      
        <h2><?php echo language::translate('title_customer_group_prices', 'Customer Group Prices'); ?></h2>

       <div class="row">
          <div class="form-group col-md-2" >
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('no_customer_group_prices', '1', true); ?> <?php echo language::translate('title_no_customer_group_prices', 'No customer Group Prices'); ?></label>
            </div>
          </div> 
          </div>
        <table id="customer-group-prices" class="table table-striped data-table">
          <thead>
          
            <tr>
               <td colspan="5"><a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
               <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
               <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
               <a class="delete_all" style="margin-left: 80px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a>
               </td>
            </tr>
            
          </thead>
          <thead>
      
            <tr>
            <th style="width: 10px;"></th>
              <th style="width: 320px;"><?php echo language::translate('title_customer_group', 'Customer Group'); ?></th>
              <th style="width: 220px; display: none;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
              <th style="width: 220px; display: none;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
              <th style="width: 320px;"><?php echo settings::get('store_currency_code'); ?></th>
              <?php foreach (array_keys(currency::$currencies) as $currency_code) { if ($currency_code == settings::get('store_currency_code')) continue; ?>
              
              <?php } ?>
              <th></th>
            </tr>
            
          </thead>
             <tbody>

 
             
          <?php if (!empty($_POST['customer_group_prices'])) foreach (array_keys($_POST['customer_group_prices']) as $key) { ?>
            <tr>              
              <td style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
              <td><?php echo functions::form_draw_hidden_field('customer_group_prices['.$key.'][id]', true) . functions::form_draw_customer_groups_list('customer_group_prices['.$key.'][customer_group_id]', true); ?></td>
              <td style="display: none;">
                <?php echo functions::form_draw_datetime_field('customer_group_prices['.$key.'][start_date]', true); ?>
              </td>
              <td style="display: none;">
                <?php echo functions::form_draw_datetime_field('customer_group_prices['.$key.'][end_date]', true); ?>
              </td>
              <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
              <td><?php echo functions::form_draw_currency_field($currency_code, 'customer_group_prices['.$key.']['. $currency_code. ']', isset($_POST['customer_group_prices'][$key][$currency_code]) ? number_format($_POST['customer_group_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
              <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
              <td style="display: none;"><?php echo functions::form_draw_currency_field($currency_code, 'customer_group_prices['.$key.']['. $currency_code. ']', isset($_POST['customer_group_prices'][$key][$currency_code]) ? number_format($_POST['customer_group_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
              <?php } ?>
              <?php } ?>
            </tr>
            <?php } ?>
            
          </tbody>
         <tfoot>
         </tfoot>
        </table>


<div class="pull-right">	<?php
		echo functions::form_draw_button('save_stay', language::translate('title_save_stay', 'Save & Stay'), 'submit', '', 'save');
		echo PHP_EOL;

		echo functions::form_draw_button('save', language::translate('title_save_exit', 'Save & Exit'), 'submit', '', 'save');
		echo PHP_EOL;
	?>
</div>



<style>
* {
  box-sizing: border-box;
}

/* Create four equal columns that floats next to each other */
.column {
  float: left;
  width: 25%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>

<h2>Reset Prices</h2>

<div class="row">
  <div class="column" style="width: 320px;">
        <label><?php echo language::translate('title_reset_guest_price', 'Reset Guest Price'); ?></label></br></br>
         <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'reset_guest_price_prices['. settings::get('store_currency_code') .']', true); ?>      
        </br>
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('sign_in', '1', true); ?> <?php echo language::translate('title_sign_in', 'Sign In'); ?></label>
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('guest_insaneprice', '1', true); ?> <?php echo language::translate('title_guest_insaneprice', 'Guest Insane Price'); ?></label>
            </div>
            
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('specialprice', '1', true); ?> <?php echo language::translate('title_specialprice', 'Special Price'); ?></label>
            </div>
                 
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('master_guest_special_price', '1', true); ?> <?php echo language::translate('master_guest_special_price', 'Master Guest Special Price'); ?></label>
            </div>
         
         <h3><?php echo language::translate('title_continuously', 'Continuously'); ?></h3> 
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('disable_master_guest_special_price', '1', true); ?> <?php echo language::translate('disable_master_guest_special_price', 'Disable Master Guest Special Price'); ?></label>
            </div>  
          </div>
  
  <div class="column" style="width: 320px;">
        <label><?php echo language::translate('title_reset_default_price', 'Reset Customer Price'); ?></label></br></br>
          <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'reset_default_price_prices['. settings::get('store_currency_code') .']', true); ?>
        </br>
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('customer_insaneprice', '1', true); ?> <?php echo language::translate('title_customer_insaneprice', 'Customer Insane Price'); ?></label>
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('customer_specialprice', '1', true); ?> <?php echo language::translate('title_customer_specialprice', 'Customer Special Price'); ?></label>
            </div>
           
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('master_customer_special_price', '1', true); ?> <?php echo language::translate('master_customer_special_price', 'Master Customer Special Price'); ?></label>
            </div> 

           </br>
           </br>
           
        <h3><?php echo language::translate('title_continuously', 'Continuously'); ?></h3>    
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('disable_master_customer_special_price', '1', true); ?> <?php echo language::translate('disable_master_customer_special_price', 'Disable Master Customer Special Price'); ?></label>
            </div>            
         </div>
  
  <div class="column" style="width: 320px;">
        <label><?php echo language::translate('title_reset_wholesale_price', 'Reset Wholesale Price'); ?></label></br></br>
          <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'reset_wholesale_price_prices['. settings::get('store_currency_code') .']', true); ?>
        </br>
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('wholesale_soldout', '1', true); ?> <?php echo language::translate('title_wholesale_soldout', 'Wholesale Sold Out'); ?></label>
            </div>

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('wholesale_specialprice', '1', true); ?> <?php echo language::translate('title_wholesale_specialprice', 'Wholesale Special Price'); ?></label>
            </div>
          
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('master_wholesale_special_price', '1', true); ?> <?php echo language::translate('master_wholesale_special_price', 'Master Wholesale Special Price'); ?></label>
            </div>

           </br>
           </br>
         
         <h3><?php echo language::translate('title_disable', 'Disable'); ?></h3>
         
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('disable_wholesale_soldout', '1', true); ?> <?php echo language::translate('title_disable_wholesale_soldout', 'Disable Wholesale Sold Out'); ?></label>
            </div>         
         
            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('disable_master_wholesale_special_price', '1', true); ?> <?php echo language::translate('disable_master_wholesale_special_price', 'Disable Master Wholesale Special Price'); ?></label>
            </div>  
            </div>
     
  
  
  <div class="column" style="width: 320px;">
    <label><?php echo language::translate('title_reset_vip_price', 'Reset VIP Price'); ?></label></br></br>
    <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'reset_fake_sold_out_date_price_prices['. settings::get('store_currency_code') .']', true); ?>
     </br> 
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('vip', '1', true); ?> <?php echo language::translate('title_vip', 'V.I.P'); ?></label>
            </div>     
            
          
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('fake_sold_out', '1', true); ?> <?php echo language::translate('title_fake_sold_out', 'Fake Sold Out'); ?></label>
            </div> 
      
       
  </div>
</div>

</br>

<script>



  $('body').on('keyup change', 'input[name^="customer_group_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="customer_group_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="customer_group_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="customer_group_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
      $(parent).find('input[name^="customer_group_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
  });
  $('input[name^="customer_group_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');
   $('body').on('change', 'select[name^="customer_group_prices"][name$="[customer_group_id]"]', function() {
        if(parseInt($(this).find("option:selected").attr('value')) === 999){
           var parent = $(this).closest('tr');
           $(parent).find('input[name^="customer_group_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val($('input[name="purchase_price"]').value);
           $(parent).find('input[name^="customer_group_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');
        }
   });

$('#customer-group-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });
  
  $('#customer-group-prices').on('click', '.delete_all', function(e) {
    e.preventDefault();
    $('#customer-group-prices tbody tr').remove();
  });
  
  $('#customer-group-prices').on('click', '.delete', function(e) {
    e.preventDefault();
    $('#customer-group-prices tbody input[name^="customer_group_prices"]').attr('value','');
    $('#customer-group-prices tbody select[name^="customer_group_prices"]==1').prop('selectedIndex', 0).selectmenu('refresh', true);;
  });  
  
$('#customer-group-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#customer-group-prices tbody tr').toggle();
});


<!-- $('#customer-group-prices tbody tr').toggle(); -->

  var new_customer_group_price_i = 1;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td ><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '1')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_1')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_1')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });  
  
  var new_customer_group_price_i = 2;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '2')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_2')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_2')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });  
  
  var new_customer_group_price_i = 3;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '3')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_3')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_3')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });  
  
  var new_customer_group_price_i = 4;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '4')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_4')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_4')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });  
  
  var new_customer_group_price_i = 5;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '5')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_5')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_5')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });  

  var new_customer_group_price_i = 6;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '6')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_6')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_6')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });
  $('')
  
  var new_customer_group_price_i = 1;
  $('#customer-group-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '<td class="text-right" style="width: 10px; padding: 15px;"><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '<td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('customer_group_prices[new_customer_group_price_i][id]', '') . functions::form_draw_customer_groups_list('customer_group_prices[new_customer_group_price_i][customer_group_id]', '999')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][start_date]', true, '', 'customer_group_999')); ?></td>'
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_datetime_field('customer_group_prices[new_customer_group_price_i][end_date]', true, '', 'customer_group_999')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
                  <?php foreach (array_keys(currency::$currencies) as $currency_code) { if (!empty($currency_code == settings::get('store_currency_code'))) continue; ?>
               + '<td style="display: none;"><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'customer_group_prices[new_customer_group_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
               <?php } ?>
               <?php } ?>
               + '</tr>';
   while ($('input[name="customer_group_prices[new_'+new_customer_group_price_i+']"]').length) new_customer_group_price_i++;
    output = output.replace(/new_customer_group_price_i/g, 'new_' + new_customer_group_price_i);
    $('#customer-group-prices tbody').append(output);
    new_customer_group_price_i++;
  });
  $('')  
  

      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>

      

        <h2><?php echo language::translate('title_quantity_prices', 'Quantity Prices'); ?></h2>
        <table id="quantity-prices" class="table table-striped">
          <thead>
            <tr>
               <td colspan="5"><a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
               <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
               <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>
               </td>
            </tr>          

          </thead>
          <thead>
            <tr>
              <th style="width: 200px;"><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
              <th colspan="<?php echo count(currency::$currencies); ?>"><?php echo language::translate('title_price', 'Price'); ?></th>
            </tr>          

          </thead>
          <tbody>
            <?php if (!empty($_POST['quantity_prices'])) foreach (array_keys($_POST['quantity_prices']) as $key) { ?>
            <tr>
              <td><?php echo functions::form_draw_hidden_field('quantity_prices['.$key.'][id]', true) . functions::form_draw_decimal_field('quantity_prices['.$key.'][quantity]', true, 2); ?></td>
              <td style="width: 250px;"><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'quantity_prices['.$key.']['. settings::get('store_currency_code') .']', true); ?></td>
           <?php
              foreach (array_keys(currency::$currencies) as $currency_code) {
                if ($currency_code == settings::get('store_currency_code')) continue;
                ?>
              <td style="width: 250px;"><?php echo functions::form_draw_currency_field($currency_code, 'quantity_prices['.$key.']['. $currency_code. ']', isset($_POST['quantity_prices'][$key][$currency_code]) ? number_format($_POST['quantity_prices'][$key][$currency_code], 4, '.', '') : '', 'data-size="small"'); ?></td>
                <?php 
                     }
                     ?>
              <td><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
          </tfoot>
        </table>

<script>
  $('body').on('keyup change', 'input[name^="quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    if ($(parent).find('input[name^="quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
      $(parent).find('input[name^="quantity_prices"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
  });
  $('input[name^="quantity_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

  var new_quantity_price_i = 1;
  $('#quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('quantity_prices[new_quantity_price_i][id]', '') . functions::form_draw_decimal_field('quantity_prices[new_quantity_price_i][quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'quantity_prices[new_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'quantity_prices[new_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php
  }
?>
               + '  <td><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '</tr>';
    while ($('input[name="quantity_prices[new_'+new_quantity_price_i+']"]').length) new_quantity_price_i++;
    output = output.replace(/new_quantity_price_i/g, 'new_' + new_quantity_price_i);
    $('#quantity-prices tbody').append(output);
    new_quantity_price_i++;
  });

  var new_quantity_price_i = 1;
  $('#quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('quantity_prices[new_quantity_price_i][id]', '') . functions::form_draw_decimal_field('quantity_prices[new_quantity_price_i][quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'quantity_prices[new_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'quantity_prices[new_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php
  }
?>
               + '  <td><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '</tr>';
    while ($('input[name="quantity_prices[new_'+new_quantity_price_i+']"]').length) new_quantity_price_i++;
    output = output.replace(/new_quantity_price_i/g, 'new_' + new_quantity_price_i);
    $('#quantity-prices tbody').append(output);
    new_quantity_price_i++;
  });

  var new_quantity_price_i = 1;
  $('#quantity-prices').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_hidden_field('quantity_prices[new_quantity_price_i][id]', '') . functions::form_draw_decimal_field('quantity_prices[new_quantity_price_i][quantity]', '', 2, 0, null, 'data-size="tiny"')); ?></td>'
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field(settings::get('store_currency_code'), 'quantity_prices[new_quantity_price_i]['. settings::get('store_currency_code') .']', '')); ?></td>'
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
               + '  <td style="width: 250px;"><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'quantity_prices[new_quantity_price_i]['. $currency_code .']', '', 'data-size="small"')); ?></td>'
<?php
  }
?>
               + '  <td><a class="remove" href="#" title="<?php echo htmlspecialchars(language::translate('title_remove', 'Remove')); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>'
               + '</tr>';
    while ($('input[name="quantity_prices[new_'+new_quantity_price_i+']"]').length) new_quantity_price_i++;
    output = output.replace(/new_quantity_price_i/g, 'new_' + new_quantity_price_i);
    $('#quantity-prices tbody').append(output);
    new_quantity_price_i++;
  });

  $('#quantity-prices').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });
  
  $('#quantity-prices').on('click', '.delete', function(e) {
      e.preventDefault();
      $('#quantity-prices tbody tr').remove();
  });
$('#quantity-prices').on('click', '.hide_show', function(e) {
    e.preventDefault();
    $('#quantity-prices tbody tr').toggle();
});
$('#quantity-pricess tbody tr').toggle();  
  
</script>
      
          <h2><?php echo language::translate('title_campaigns', 'Campaigns'); ?></h2>

<div class="pull-right">	<?php
		echo functions::form_draw_button('save_stay', language::translate('title_save_stay', 'Save & Stay'), 'submit', '', 'save');
		echo PHP_EOL;

		echo functions::form_draw_button('save', language::translate('title_save_exit', 'Save & Exit'), 'submit', '', 'save');
		echo PHP_EOL;
	?>
</div>

          <div class="form-group col-md-2">

           <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('sign_in_deal', '1', true); ?> <?php echo language::translate('title_sign_in_deal', 'Sign In'); ?></label>
            </div>
            
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('insaneprice', '1', true); ?> <?php echo language::translate('title_insaneprice', 'Insane Price'); ?></label>
            </div>
            
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('free_shipping', '1', true); ?> <?php echo language::translate('free_shipping', 'Free Shipping'); ?></label>
            </div>             

            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('no_free_shipping', '1', true); ?> <?php echo language::translate('no_free_shipping', 'No Free Shipping'); ?></label>
            </div>  

            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('master_insane_deal_price', '1', true); ?> <?php echo language::translate('master_insane_deal_price', 'Master Insane Deal Price'); ?></label>
            </div>

            <div class="checkbox" >
              <label><?php echo functions::form_draw_checkbox('disable_master_insane_deal_price', '1', true); ?> <?php echo language::translate('disable_master_insane_deal_price', 'Disable Master Insane Deal Price'); ?></label>
            </div>
          </div>
		</br>	
     <div class="row">
      <div class="form-group col-md-3" style="max-width: 320px;">
      <label><?php echo language::translate('title_reset_insane_deal', 'Reset Insane Deal'); ?></label></br></br>
        <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'reset_campaigns['. settings::get('store_currency_code') .']', true); ?>
      </div>			
       </div>
      </br>
      
          
          <div class="table-responsive">
            <table id="table-campaigns" class="table table-striped data-table">

			<thead>
            <td colspan="5"><a class="add" href="#" title="<?php echo htmlspecialchars(language::translate('title_add', 'Add')); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?></a>
            <a class="delete" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_delete', 'Delete')); ?>"><?php echo functions::draw_fonticon('fa-trash-o', 'style="color: #ff6e6e;"'); ?></a>
            <a class="hide_show" style="margin-left: 20px;" href="#" title="<?php echo htmlspecialchars(language::translate('title_toggle', 'Toggle')); ?>"><?php echo functions::draw_fonticon('fa-eye-slash', 'style="color: #6d6ff7;"'); ?></a>

            </td>
            </thead>
            <thead>
            <tr>
            <th style="width: 10px;"></th>
                <th style="width: 320px;"><?php echo language::translate('title_start_date', 'Start Date'); ?></th>
                <th style="width: 220px;"><?php echo language::translate('title_end_date', 'End Date'); ?></th>
                <td style="display: none;"><?php echo language::translate('title_percentage', 'Percentage'); ?></th>
                <th style="width: 320px;"><?php echo settings::get('store_currency_code'); ?></th>
               
   <!--             <?php foreach (array_keys(currency::$currencies) as $currency_code) {
                    if ($currency_code == settings::get('store_currency_code')) continue;
                    ?>
                    <th style="width: 320px;"><?php echo $currency_code; ?></th>
                <?php } ?>
    -->            
               <th></th><th></th>
            </tr>
            </thead>
      
              <tbody>
                <?php if (!empty($_POST['campaigns'])) foreach (array_keys($_POST['campaigns']) as $key) { ?>
                <tr>
                  <td><br /><a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"'); ?></a></td>                
                  <td><?php echo language::translate('title_start_date', 'Start Date'); ?><br />
                    <?php echo functions::form_draw_hidden_field('campaigns['.$key.'][id]', true) . functions::form_draw_datetime_field('campaigns['.$key.'][start_date]', true); ?>
                  </td>
                  <td><?php echo language::translate('title_end_date', 'End Date'); ?><br />
                    <?php echo functions::form_draw_datetime_field('campaigns['.$key.'][end_date]', true); ?>
                  </td>

                  <td><?php echo settings::get('store_currency_code'); ?><br />
                    <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'campaigns['.$key.']['. settings::get('store_currency_code') .']', true); ?>
                  </td> 
                  
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
                  
            <td style="display: none;"><?php echo $currency_code; ?><br />
      
                  <?php echo functions::form_draw_currency_field($currency_code, 'campaigns['.$key.']['. $currency_code. ']', isset($_POST['campaigns'][$key][$currency_code]) ? number_format((float)$_POST['campaigns'][$key][$currency_code], 4, '.', '') : ''); ?>
                  </td>
<?php
  }
?>

                </tr>
              </tbody>
              <?php } ?>
              <tfoot>
                <tr>
                  
          <!--  <td colspan="<?php echo 5 + count(currency::$currencies) - 1; ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?> <a class="add" href="#"><?php echo language::translate('text_add_campaign', 'Add Campaign'); ?></a></td> -->
            </td>
      
                </tr>
              </tfoot>
            </table>
                
      







































          </div>
        </div>

        <div id="tab-options" class="tab-pane">
          <h2><?php echo language::translate('title_options', 'Options'); ?></h2>
          <div class="table-responsive">
            <table id="table-options" class="table table-striped data-table">
              <thead>
                <tr>
<?php
        if (!empty($_POST['images'])) { 
        ?>
        <div id="image-for-option" class="lightbox" style="display: none; max-width: 640px;">
        <h3 class="title"><?php echo language::translate('title_images') ?></h3>
        <a href="#" data-image-id="0" class="set_image_id_for_option">
          <div class="thumbnail pull-left" style="margin:5px">
            <img style="max-width:100px;max-height:100px" data-id="0" src="<?php echo document::href_link(WS_DIR_HTTP_HOME . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . 'no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />
          </div>
        </a>         
        <?php foreach (array_keys($_POST['images']) as $key) { ?>
          <a href="#" data-image-id="<?php echo $key ?>" class="set_image_id_for_option">
                <div class="thumbnail pull-left" style="margin:5px 10px">
                  <img style="max-width:100px;max-height:100px" data-id="<?php echo $key ?>" src="<?php echo document::href_link(WS_DIR_HTTP_HOME . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product->data['images'][$key]['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />
                </div>
          </a>        
        <?php } ?>
      </div>
        <?php }
        if(database::fetch(database::query("SHOW COLUMNS FROM ". DB_TABLE_PRODUCTS_OPTIONS ."  LIKE 'image_id'")) === NULL){
          database::query("ALTER TABLE ". DB_TABLE_PRODUCTS_OPTIONS ." ADD `image_id` INT");          
        } ?>
        <th>&nbsp;</th>
      
                  <th style="min-width: 200px;"><?php echo language::translate('title_group', 'Group'); ?></th>
                  <th style="min-width: 200px;"><?php echo language::translate('title_value', 'Value'); ?></th>
                  <th style="width: 50px;"><?php echo language::translate('title_price_operator', 'Price Operator'); ?></th>
                  <th style="width: 200px;"><?php echo language::translate('title_price_adjustment', 'Price Adjustment'); ?></th>
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
                <th class="text-center" style="width: 200px;"></th>
<?php
  }
?>
                  <th style="width: 85px;">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
<?php
  if (!empty($_POST['options'])) {
    foreach (array_keys($_POST['options']) as $key) {
?>
              <tr>
<td>
      <?php $image_id = isset($product->data['options'][$key]['image_id'])?(int)$product->data['options'][$key]['image_id']:0; ?>
      <a href="#" data-option_id="<?php echo $key ?>" class="set_image_for_option" data-toggle="lightbox" data-target="#image-for-option">
      <div class="thumbnail pull-left">
          <input id="inp-option_image_<?php echo $key ?>" type="hidden" name="options[<?php echo $key ?>][image_id]" value="<?php echo $image_id ?>">
          <?php
            if($image_id > 0 && isset($product->data['images'][$image_id]) && !empty($product->data['images'][$image_id]['filename'])){
              echo '<img style="max-width:25px;max-height:25px" class="main-image" src="'. document::href_link(WS_DIR_HTTP_HOME . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product->data['images'][$image_id]['filename'], $product_image_width, $product_image_height, settings::get('product_image_clipping'))) .'" alt="" />';
            } else { 
              echo '<img style="max-width:25px;max-height:25px" class="main-image" src="'. document::href_link(WS_DIR_HTTP_HOME . functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . 'no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))) .'" alt="" />'; 
          } ?>
      </div>
      </a>
      </td>
                <td><?php echo functions::form_draw_option_groups_list('options['.$key.'][group_id]', true); ?></td>
                <td><?php echo functions::form_draw_option_values_list($_POST['options'][$key]['group_id'], 'options['.$key.'][value_id]', true); ?></td>
                <td style="text-align: center;"><?php echo functions::form_draw_select_field('options['.$key.'][price_operator]', array('+','%','*'), $_POST['options'][$key]['price_operator']); ?></td>
                <td><?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'options['.$key.']['.settings::get('store_currency_code').']', true); ?></td>
<?php
      foreach (array_keys(currency::$currencies) as $currency_code) {
        if ($currency_code == settings::get('store_currency_code')) continue;
?>
                <td><?php echo str_replace(PHP_EOL, '', functions::form_draw_currency_field($currency_code, 'options['.$key.']['. $currency_code. ']', number_format((float)$_POST['options'][$key][$currency_code], 4, '.', ''))); ?></td>
<?php
      }
?>
                <td class="text-right"><a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a> <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a> <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a></td>
              </tr>
<?php
    }
  }
?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="<?php echo 5 + count(currency::$currencies); ?>"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?> <a class="add" href="#"><?php echo language::translate('title_add_option', 'Add Option'); ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <div id="tab-stock" class="tab-pane">

          <h2><?php echo language::translate('title_stock', 'Stock'); ?></h2>


         <div class="row" style="max-width: 960px;">
          <div class="form-group col-md-4">
            <label><?php echo language::translate('title_min_qty', 'Minimum Quantity'); ?></label>
            <?php echo functions::form_draw_decimal_field('min_qty', true); ?>
          </div>

          <div class="form-group col-md-4">
            <label><?php echo language::translate('title_max_qty', 'Maximum Quantity'); ?></label>
            <?php echo functions::form_draw_decimal_field('max_qty', true); ?>
          </div> 
          
          <div class="form-group col-md-4">
      <label><?php echo language::translate('title_opening_quantity', 'Opening Quantity'); ?></label>
        
      <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'opening_quantity['. $language_code .']', true, 'style="height: 32px;"'); ?>
          </div> 
          </div>
         <div class="row" style="max-width: 960px;">
      <div class="form-group col-md-4">
      <label><?php echo language::translate('title_small_parcel', 'Small Size Parcel'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'small_parcel['. $language_code .']', true, 'style="height: 32px;"'); ?>      
      </div>           
         
         
      <div class="form-group col-md-4">
      <label><?php echo language::translate('title_medium_parcel', 'Medium Size Parcel'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'medium_parcel['. $language_code .']', true, 'style="height: 32px;"'); ?>      
      </div>         
         
         
          <div class="form-group col-md-4">
      <label><?php echo language::translate('title_oversize_parcel', 'Oversize Parcel'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_textarea($language_code, 'oversize_parcel['. $language_code .']', true, 'style="height: 32px;"'); ?>
      </div>
      

      </div>
      


      
          <div class="row" style="max-width: 960px;">
            <div class="form-group col-md-4">
              <label><?php echo language::translate('title_quantity_unit', 'Quantity Unit'); ?></label>
              <?php echo functions::form_draw_quantity_units_list('quantity_unit_id', true, false); ?>
            </div>

            <div class="form-group col-md-4">
              <label><?php echo language::translate('title_delivery_status', 'Delivery Status'); ?></label>
              <?php echo functions::form_draw_delivery_statuses_list('delivery_status_id', true); ?>
            </div>

            <div class="form-group col-md-4">
              <label><?php echo language::translate('title_sold_out_status', 'Sold Out Status'); ?></label>
              <?php echo functions::form_draw_sold_out_statuses_list('sold_out_status_id', true); ?>
            </div>
          </div>

          <div class="table-responsive">
            <table id="table-stock" class="table table-striped table-hover data-table">
              <thead>
                <tr>
                  <th><?php echo language::translate('title_option', 'Option'); ?></th>
                  <th class="text-center" style="width: 200px;"><?php echo language::translate('title_sku', 'SKU'); ?></th>
                  <th class="text-center" style="width: 185px;"><?php echo language::translate('title_weight', 'Weight'); ?></th>
                  <th style="width: 400px;"><?php echo language::translate('title_dimensions', 'Dimensions'); ?></th>
                  <th class="text-center" style="width: 125px;"><?php echo language::translate('title_qty', 'Qty'); ?></th>
                  <th style="width: 85px;">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong><?php echo language::translate('title_default_item', 'Default Item'); ?></strong></td>
                  <td><?php echo functions::form_draw_text_field('sku', true); ?></td>
                  <td>
                    <div class="input-group">
                      <?php echo functions::form_draw_decimal_field('weight', true, 4, 0); ?>
                      <span class="input-group-addon">
                        <?php echo functions::form_draw_weight_classes_list('weight_class', true, false, 'style="width: auto;"'); ?>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="input-group">
                      <?php echo functions::form_draw_decimal_field('dim_x', true, 4, 0); ?>
                      <?php echo functions::form_draw_decimal_field('dim_y', true, 4, 0); ?>
                      <?php echo functions::form_draw_decimal_field('dim_z', true, 4, 0); ?>
                      <span class="input-group-addon">
                        <?php echo functions::form_draw_length_classes_list('dim_class', true, false, 'style="width: auto;"'); ?>
                      </span>
                    </div>
                  </td>
                  <td><?php echo functions::form_draw_decimal_field('quantity', true); ?></td>
                  <td></td>
                </tr>
                <?php if (!empty($_POST['options_stock'])) foreach (array_keys($_POST['options_stock']) as $key) { ?>
                <tr>
                  <td><?php echo functions::form_draw_hidden_field('options_stock['.$key.'][id]', true); ?><?php echo functions::form_draw_hidden_field('options_stock['.$key.'][combination]', true); ?>
                    <?php echo functions::form_draw_hidden_field('options_stock['.$key.'][name]['. language::$selected['name'] .']', true); ?>
                    <?php echo $_POST['options_stock'][$key]['name'][language::$selected['code']]; ?></td>
                  <td><?php echo functions::form_draw_text_field('options_stock['.$key.'][sku]', true); ?></td>
                  <td>
                    <div class="input-group">
                      <?php echo functions::form_draw_decimal_field('options_stock['.$key.'][weight]', true, 4, 0); ?>
                      <span class="input-group-addon">
                        <?php echo functions::form_draw_weight_classes_list('options_stock['.$key.'][weight_class]', true, false, 'style="width: auto;"'); ?>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="input-group">
                      <?php echo functions::form_draw_decimal_field('options_stock['.$key.'][dim_x]', true, 4, 0); ?>
                      <?php echo functions::form_draw_decimal_field('options_stock['.$key.'][dim_y]', true, 4, 0); ?>
                      <?php echo functions::form_draw_decimal_field('options_stock['.$key.'][dim_z]', true, 4, 0); ?>
                      <span class="input-group-addon">
                        <?php echo functions::form_draw_length_classes_list('options_stock['.$key.'][dim_class]', true, false, 'style="width: auto;"'); ?>
                      </span>
                    </div>
                  </td>
                  <td><?php echo functions::form_draw_decimal_field('options_stock['.$key.'][quantity]', true); ?></td>
                  <td class="text-right">
                    <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>
                    <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>
                    <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?> <a href="#" data-toggle="lightbox" data-target="#new-stock-option"><?php echo language::translate('title_add_stock_option', 'Add Stock Option'); ?></a></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div id="new-stock-option" class="lightbox" style="display: none; max-width: 640px;">
            <h3 class="title"><?php echo language::translate('title_new_stock_option', 'New Stock Option'); ?></h3>

            <table class="table table-striped" style="width: 100%;">
              <thead>
                <tr>
                  <th style="width: 50%;"><?php echo language::translate('title_group', 'Group'); ?></th>
                  <th style="width: 50%;"><?php echo language::translate('title_value', 'Value'); ?></th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo functions::form_draw_option_groups_list('new_option[new_1][group_id]', ''); ?></td>
                  <td><?php echo functions::form_draw_select_field('new_option[new_1][value_id]', array(array('','')), '', 'disabled="disabled"'); ?></td>
                  <td><a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?> <a class="add" href="#" title="<?php echo language::translate('text_add', 'Add'); ?>"><?php echo language::translate('title_add_to_combination', 'Add To Combination'); ?></a></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </tfoot>
            </table>

            <button type="button" class="btn btn-default" name="add_stock_option"><?php echo language::translate('title_add_stock_option', 'Add Stock Option'); ?></button>
          </div>
        </div>

            <?php
            if(!empty($_GET['product_id'])){
             $wishlist_query = database::query(
        "select * from ". DB_TABLE_WISHLIST ." where  product_id = '" . (int)$_GET['product_id'] ."';"
    );
            ?>
        <div id="tab-wishlist" class="tab-pane">
           <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title"><?php echo language::translate('title_wishlist_customers', 'WishList Customers'); ?></h2>
        </div>
        <div class="panel-body table-responsive">
          <table class="table table-striped table-hover table-input data-table">
            <thead>
              <tr>
                <th><?php echo language::translate('title_name', 'Name'); ?></th>
                <th style="width: 200px;"><?php echo language::translate('title_phone', 'Phone'); ?></th>
                <th style="width: 200px;"><?php echo language::translate('title_email', 'Email'); ?></th>
                <th style="width: 200px;"><?php echo language::translate('title_date', 'Date'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php while($wishlist = database::fetch($wishlist_query)) {
              $customer = new ent_customer($wishlist['customer_id']);?>
              <tr class="item">
                <td>
                <a href="<?php echo document::href_link('', array('app' => 'customers', 'doc' => 'edit_customer', 'customer_id' => $wishlist['customer_id']), true); ?>"><?php echo $customer->data['firstname'].' '.$customer->data['lastname'] ;?> </a>
              </td>
              <td class="date"><?php echo $customer->data['phone']; ?></td>
              <td class="date"><a href="<?php echo document::href_link('', array('app' => 'customers', 'doc' => 'edit_customer', 'customer_id' => $wishlist['customer_id']), true); ?>"><?php echo $customer->data['email']; ?></td>
              <td class="date"><?php echo $wishlist['created_at']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
        </div>
        <?php }?>

      
      </div>

      <div class="panel-action btn-group">
        
	<?php
		echo functions::form_draw_button('save_stay', language::translate('title_save_stay', 'Save & Stay'), 'submit', '', 'save');
		echo PHP_EOL;

		echo functions::form_draw_button('save', language::translate('title_save_exit', 'Save & Exit'), 'submit', '', 'save');
		echo PHP_EOL;
	?>
		
        <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
        <?php echo (isset($product->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>
</div>

<script>

       window.onscroll = function() {myFunction()};

       var navbar = document.getElementById("navbar");
       var sticky = navbar.offsetTop;

       function myFunction() {
         if (window.pageYOffset >= sticky) {
           navbar.classList.add("sticky")
         } else {
           navbar.classList.remove("sticky");
         }
       }
       
$(document).ready(function(){
  $(".menu-btn").on('click',function(e){
    e.preventDefault();
    
    //Check this block is open or not..
    if(!$(this).prev().hasClass("open")) {
      $(".header").slideDown(400);
      $(".header").addClass("open");
      $(this).find("i").removeClass().addClass("fa fa-chevron-up");
    }
    
    else if($(this).prev().hasClass("open")) {
      $(".header").removeClass("open");
      $(".header").slideUp(400);
      $(this).find("i").removeClass().addClass("fa fa-chevron-down");
    }
  });
});       
      

// Initiate

  $('input[name^="name"]').bind('input propertyChange', function(e){
    var language_code = $(this).attr('name').match(/\[(.*)\]$/)[1];
    $('input[name="head_title['+language_code+']"]').attr('placeholder', $(this).val());
    $('input[name="h1_title['+language_code+']"]').attr('placeholder', $(this).val());
  }).trigger('input');

  $('input[name^="short_description"]').bind('input propertyChange', function(e){
    var language_code = $(this).attr('name').match(/\[(.*)\]$/)[1];
    $('input[name="meta_description['+language_code+']"]').attr('placeholder', $(this).val());
  }).trigger('input');

// Default Category

  $('input[name="categories[]"]').change(function() {
    if ($(this).is(':checked')) {
      if ($(this).val() == '<?php echo $product->data['default_category_id']; ?>') {
        $('select[name="default_category_id"]').append('<option value="'+ $(this).val() +'" selected="selected">'+ $(this).data('name') +'</option>');
      } else {
        $('select[name="default_category_id"]').append('<option value="'+ $(this).val() +'">'+ $(this).data('name') +'</option>');
      }
    } else {
      $('select[name="default_category_id"] option[value="'+ $(this).val() +'"]').remove();
    }
    var default_category = $('select[name="default_category_id"] option:selected').val();
    $('select[name="default_category_id"]').html($('select[name="default_category_id"] option').sort(function(a,b){
        a = $('input[name="categories[]"][value="'+ a.value +'"]').data('priority');
        b = $('input[name="categories[]"][value="'+ b.value +'"]').data('priority');
        return a-b;
    }));
    $('select[name="default_category_id"] option').prop('selected', '');
    $('select[name="default_category_id"] option[value="'+ default_category +'"]').prop('selected', 'selected');
  });

  $('input[name="categories[]"]:checked').trigger('change');

// SKU

  $('input[name="sku"]').change(function() {
    $('input[name="sku"]').not(this).val($(this).val());
  });

// Images

  $('#images').on('click', '.move-up, .move-down', function(e) {
    e.preventDefault();
    var row = $(this).closest('.form-group');

    if ($(this).is('.move-up') && $(row).prevAll().length > 0) {
      $(row).insertBefore(row.prev());
    } else if ($(this).is('.move-down') && $(row).nextAll().length > 0) {
      $(row).insertAfter($(row).next());
    }
    refreshMainImage();
  });

  $('#images').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('.form-group').remove();
    refreshMainImage();
  });


      $('#images .add').click(function(e) {
    e.preventDefault();
    var output = '<div class="image form-group">'
               + '  <div class="thumbnail pull-left">'
               + '    <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />'
               + '  </div>'
               + '  '
               + '  <div class="input-group">'
               + '    <?php echo functions::form_draw_file_field('new_images[]'); ?>'
               + '    <div class="input-group-addon">'
               + '      <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>'
               + '    </div>'
               + '  </div>'
               + '</div>';
    $('#images .new-images').append(output);
    refreshMainImage();
  });
    
      $('#images .add').click(function(e) {
    e.preventDefault();
    var output = '<div class="image form-group">'
               + '  <div class="thumbnail pull-left">'
               + '    <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />'
               + '  </div>'
               + '  '
               + '  <div class="input-group">'
               + '    <?php echo functions::form_draw_file_field('new_images[]'); ?>'
               + '    <div class="input-group-addon">'
               + '      <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>'
               + '    </div>'
               + '  </div>'
               + '</div>';
    $('#images .new-images').append(output);
    refreshMainImage();
  });
  
      
  $('#images .add').click(function(e) {
    e.preventDefault();
    var output = '<div class="image form-group">'
               + '  <div class="thumbnail pull-left">'
               + '    <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>" alt="" />'
               + '  </div>'
               + '  '
               + '  <div class="input-group">'
               + '    <?php echo functions::form_draw_file_field('new_images[]'); ?>'
               + '    <div class="input-group-addon">'
               + '      <a class="move-up" href="#" title="<?php echo language::translate('text_move_up', 'Move up'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="move-down" href="#" title="<?php echo language::translate('text_move_down', 'Move down'); ?>"><?php echo functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"'); ?></a>'
               + '      <a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a>'
               + '    </div>'
               + '  </div>'
               + '</div>';
    $('#images .new-images').append(output);
    refreshMainImage();
  });

  $('#images').on('change', 'input[type="file"]', function(e) {
    var img = $(this).closest('.form-group').find('img');

    var oFReader = new FileReader();
    oFReader.readAsDataURL(this.files[0]);
    oFReader.onload = function(e){
      $(img).attr('src', e.target.result);
    };
    oFReader.onloadend = function(e) {
      refreshMainImage();
    };
  });

  function refreshMainImage() {
    if ($('#images img:first').length) {
      $('#tab-general .main-image').attr('src', $('#images img:first').attr('src'));
      return;
    }

    $('#tab-general .main-image').attr('src', '<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/no_image.png', $product_image_width, $product_image_height, settings::get('product_image_clipping'))); ?>');
  }

// Technical Data

  $('a.technical-data-hint').click(function(e){
    e.preventDefault();
    alert('Syntax:\n\nTitle1\nProperty1: Value1\nProperty2: Value2\n\nTitle2\nProperty3: Value3...');
  });

// Attributes

  $('select[name="new_attribute[group_id]"]').change(function(){
    $('body').css('cursor', 'wait');
    $.ajax({
      url: '<?php echo document::link(WS_DIR_ADMIN, array('doc' => 'attribute_values.json'), array('app')); ?>&group_id=' + $(this).val(),
      type: 'get',
      cache: true,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR.readyState + '\n' + textStatus + '\n' + errorThrown.message);
      },
      success: function(data) {
        $('select[name="new_attribute[value_id]"').html('');
        if ($('select[name="new_attribute[value_id]"').attr('disabled')) $('select[name="attribute[value_id]"]').removeAttr('disabled');
        if (data) {
          $('select[name="new_attribute[value_id]"').append('<option value="0">-- <?php echo language::translate('title_select', 'Select'); ?> --</option>');
          $.each(data, function(i, zone) {
            $('select[name="new_attribute[value_id]"').append('<option value="'+ zone.id +'">'+ zone.name +'</option>');
          });
        } else {
          $('select[name="new_attribute[value_id]"').attr('disabled', 'disabled');
        }
      },
      complete: function() {
        $('body').css('cursor', 'auto');
      }
    });
  });

  var new_attribute_i = 0;
  $('#tab-attributes button[name="add"]').click(function(){

    if ($('select[name="new_attribute[group_id]"]').val() == '') {
      alert("<?php echo language::translate('error_must_select_attribute_group', 'You must select an attribute group'); ?>");
      return;
    }

    if ($('select[name="new_attribute[value_id]"]').val() == '' || $('select[name="new_attribute[value_id]"]').val() == '0') {
      if ($('input[name="new_attribute[custom_value]"]').val() == '') {
        alert("<?php echo language::translate('error_must_select_attribute_value', 'You must select an attribute value'); ?>");
        return;
      }
    } else {
      if ($('input[name="new_attribute[custom_value]"]').val() != '') {
        alert("<?php echo language::translate('error_cannot_define_both_value_and_custom_value', 'You can not define both a value and a custom value'); ?>");
        return;
      }
    }

    var output = '<tr>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][id]', '')); ?>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][group_id]', 'new_group_id')); ?>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][group_name]', 'new_group_name')); ?>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][value_id]', 'new_value_id')); ?>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][value_name]', 'new_value_name')); ?>'
               + '  <?php echo functions::general_escape_js(functions::form_draw_hidden_field('attributes[new_attribute_i][custom_value]', 'new_custom_value')); ?>'
               + '  <td>new_group_name</td>'
               + '  <td>new_value_name</td>'
               + '  <td>new_custom_value</td>'
               + '  <td class="text-right"><a class="remove" href="#" title="<?php echo language::translate('title_remove', 'Remove'); ?>"><?php echo functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"'); ?></a></td>'
               + '</tr>';

    while ($('input[name="attributes[new_'+new_attribute_i+']"]').length) new_attribute_i++;
    output = output.replace(/new_attribute_i/g, 'new_' + new_attribute_i);
    output = output.replace(/new_group_id/g, $('select[name="new_attribute[group_id]"] option:selected').val());
    output = output.replace(/new_group_name/g, $('select[name="new_attribute[group_id]"] option:selected').text());
    output = output.replace(/new_value_id/g, $('select[name="new_attribute[value_id]"] option:selected').val());
    if ($('select[name="new_attribute[value_id]"] option:selected').val() != '0') {
      output = output.replace(/new_value_name/g, $('select[name="new_attribute[value_id]"] option:selected').text());
    } else {
      output = output.replace(/new_value_name/g, '');
    }
    output = output.replace(/new_custom_value/g, $('input[name="new_attribute[custom_value]"]').val());
    new_attribute_i++;

    $('#tab-attributes tbody').append(output);
  });

  $('#tab-attributes tbody').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });

// Prices

  function get_tax_rate() {
    switch ($('select[name=tax_class_id]').val()) {
<?php
  $tax_classes_query = database::query(
    "select * from ". DB_TABLE_TAX_CLASSES ."
    order by name asc;"
  );
  while ($tax_class = database::fetch($tax_classes_query)) {
    echo '      case "'. $tax_class['id'] . '": return '. tax::get_tax(100, $tax_class['id'], 'store') .';' . PHP_EOL;
  }
?>
      default: return 0;
    }
  }

  function get_currency_value(currency_code) {
    switch (currency_code) {
<?php
  foreach (currency::$currencies as $currency) {
    echo '      case \''. $currency['code'] .'\': return '. $currency['value'] .';' . PHP_EOL;
  }
?>
    }
  }

  function get_currency_decimals(currency_code) {
    switch (currency_code) {
<?php
  foreach (currency::$currencies as $currency) {
    echo '      case \''. $currency['code'] .'\': return '. ($currency['decimals']+2) .';' . PHP_EOL;
  }
?>
    }
  }

// Update prices
  $('select[name="tax_class_id"]').change('change', function(){
    $('input[name^="prices"]').trigger('change');
  });

// Update gross price
  $('input[name^="prices"]').bind('input change', function() {
    var currency_code = $(this).attr('name').match(/^prices\[([A-Z]{3})\]$/)[1],
        currency_decimals = get_currency_decimals(currency_code),
        net_field = $('input[name="prices['+ currency_code +']"]'),
        net_price = Number($(this).val()),
        gross_field = $('input[name="gross_prices['+ currency_code +']"]'),
        gross_price = Number($(this).val()) * (1+(get_tax_rate()/100));

    if (net_price != 0) {
      $(gross_field).val(Number(gross_price).toFixed(currency_decimals));
    } else {
      $(net_field).val('');
      $(gross_field).val('');
    }

    update_currency_prices();
  }).trigger('change');

// Update net price
  $('input[name^="gross_prices"]').bind('input change', function() {
    var currency_code = $(this).attr('name').match(/^gross_prices\[([A-Z]{3})\]$/)[1],
        currency_decimals = get_currency_decimals(currency_code),
        net_field = $('input[name="prices['+ currency_code +']"]'),
        net_price = Number($(this).val()) / (1+(get_tax_rate()/100)),
        gross_field = $('input[name="gross_prices['+ currency_code +']"]'),
        gross_price = Number($(this).val());

    if (gross_price != 0) {
      $(net_field).val(Number(net_price).toFixed(currency_decimals));
    } else {
      $(gross_price).val();
      $(net_field).val('');
    }

    update_currency_prices();
  });

// Update currency price placeholders
  function update_currency_prices() {
    var store_currency_code = '<?php echo settings::get('store_currency_code'); ?>',
        currencies = ['<?php echo implode("','", array_keys(currency::$currencies)); ?>'],
        net_price = $('input[name^="prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val(),
        gross_price = $('input[name^="gross_prices"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val();

    $.each(currencies, function(i,currency_code){
      if (currency_code == '<?php echo settings::get('store_currency_code'); ?>') return;

      var currency_decimals = get_currency_decimals(currency_code),
          currency_net_price = net_price * get_currency_value(currency_code);
          currency_gross_price = gross_price * get_currency_value(currency_code);

      $('input[name="prices['+ currency_code +']"]').attr('placeholder', currency_net_price ? Number(currency_net_price).toFixed(currency_decimals) : '')
      $('input[name="gross_prices['+ currency_code +']"]').attr('placeholder', currency_gross_price ? Number(currency_gross_price).toFixed(currency_decimals) : '')
    });
  }

  $('#price-incl-tax-tooltip').click(function(e) {
    e.preventDefault;
    alert('<?php echo str_replace(array("\r", "\n", "'"), array("", "", "\\'"), language::translate('tooltip_field_price_incl_tax', 'This field helps you calculate net price based on the store region tax. All prices input to database are always excluding tax.')); ?>');
  });

// Campaigns


        $('body').on('keyup change', 'input[name="reset_campaigns[<?php echo settings::get('store_currency_code'); ?>]"]', function() { 
      $('input[name^="campaigns"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').each(function(){
         $(this).val($('input[name="reset_campaigns[<?php echo settings::get('store_currency_code'); ?>]"]').val());
         $(this).trigger('keyup');
       });
      }); 
      
  $('#table-campaigns').on('keyup change input', 'input[name^="campaigns"][name$="[percentage]"]', function() {
    var parent = $(this).closest('tr');

    <?php foreach (currency::$currencies as $currency) { ?>
    if ($('input[name^="prices"][name$="[<?php echo $currency['code']; ?>]"]').val() > 0) {
      var value = $('input[name="prices[<?php echo $currency['code']; ?>]"]').val() * ((100 - $(this).val()) / 100);
      value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
      $(parent).find('input[name$="[<?php echo $currency['code']; ?>]"]').val(value);
    } else {
      $(parent).find('input[name$="[<?php echo $currency['code']; ?>]"]').val("");
    }
    <?php } ?>

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = $(parent).find('input[name^="campaigns"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="campaigns"][name$="[<?php echo $currency['code']; ?>]"]').attr('placeholder', value);
    <?php } ?>
  });

  $('#table-campaigns').on('keyup change input', 'input[name^="campaigns"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]', function() {
    var parent = $(this).closest('tr');
    var percentage = ($('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() - $(this).val()) / $('input[name="prices[<?php echo settings::get('store_currency_code'); ?>]"]').val() * 100;
    percentage = Number(percentage).toFixed(2);
    $(parent).find('input[name$="[percentage]"]').val(percentage);

    <?php foreach (currency::$currencies as $currency) { ?>
    var value = 0;
    value = $(parent).find('input[name^="campaigns"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').val() * <?php echo $currency['value']; ?>;
    value = Number(value).toFixed(<?php echo $currency['decimals']; ?>);
    $(parent).find('input[name^="campaigns"][name$="[<?php echo $currency['code']; ?>]"]').attr("placeholder", value);
    if ($(parent).find('input[name^="campaigns"][name$="[<?php echo $currency['code']; ?>]"]').val() == 0) {
      $(parent).find('input[name^="campaigns"][name$="[<?php echo $currency['code']; ?>]"]').val('');
    }
    <?php } ?>
  });
  $('input[name^="campaigns"][name$="[<?php echo settings::get('store_currency_code'); ?>]"]').trigger('keyup');

  
           $('#table-campaigns').on('click', '.remove', function(e) {
             e.preventDefault();
             $(this).closest('tr').remove();
         });
         $('#table-campaigns').on('click', '.delete', function(e) {
             e.preventDefault();
             $('#table-campaigns tbody tr').remove();
         });
         
         $('#table-campaigns').on('click', '.hide_show', function(e) {
             e.preventDefault();
             $('#table-campaigns tbody tr').toggle();
         });
       <!--  $('#table-campaigns tbody tr').toggle(); -->
         
      




  
              var new_campaign_i = 1;
  $('#table-campaigns').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td><br /><a class="remove" style="width: 10px;" href="#" title="<?php echo functions::general_escape_js(language::translate('title_remove', 'Remove'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #ff6e6e;"')); ?></a></td>'
               + '  <td><?php echo functions::general_escape_js(language::translate('title_start_date', 'Start Date')); ?><br />'
               + '    <?php echo functions::general_escape_js(functions::form_draw_hidden_field('campaigns[new_campaign_i][id]', '') . functions::form_draw_datetime_field('campaigns[new_campaign_i][start_date]', true, '', 'campaigns_price_1')); ?></td>'
               + '  </td>'
               + '  <td><?php echo functions::general_escape_js(language::translate('title_end_date', 'End Date')); ?><br />'
               + '    <?php echo functions::general_escape_js(functions::form_draw_datetime_field('campaigns[new_campaign_i][end_date]', true, '', 'campaigns_price_1')); ?></td>'
               + '  </td>'
               + '  <td style="display: none;">- %<br />'
               + '  <td style="display: none;">  <?php echo functions::general_escape_js(functions::form_draw_decimal_field('campaigns[new_campaign_i][percentage]', '', 2, 0, null)); ?>'
               + '  </td>'
               + '  <td><?php echo functions::general_escape_js(settings::get('store_currency_code')); ?><br />'
               + '    <?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'campaigns[new_campaign_i]['. settings::get('store_currency_code') .']', '')); ?>'
               + '  </td>'
                  <?php
                    foreach (array_keys(currency::$currencies) as $currency_code) {
                      if ($currency_code == settings::get('store_currency_code')) continue;
                  ?>
               + '  <td style="display: none;"><?php echo functions::general_escape_js($currency_code); ?><br />'
               + '    <?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'campaigns[new_campaign_i]['. $currency_code .']', '')); ?>'
               + '  </td>'
<?php
  }
?>
               + '</tr>';
   while ($('input[name="campaigns[new_'+new_campaign_i+']"]').length) new_campaign_i++;
    output = output.replace(/new_campaign_i/g, 'new_' + new_campaign_i);
    $('#table-campaigns tbody').append(output);
    new_campaign_i++;
  });			

      

































// Options

  $('#table-options').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });

  $('#table-options').on('click', '.move-up, .move-down', function(e) {
    e.preventDefault();
    var row = $(this).closest('tr');
    if ($(this).is('.move-up') && $(row).prevAll().length > 1) {
      $(row).insertBefore($(row).prev());
    } else if ($(this).is('.move-down') && $(row).nextAll().length > 0) {
      $(row).insertAfter($(row).next());
    }
  });

  $('#table-options').on('change', 'select[name^="options"][name$="[group_id]"]', function(){
    var valueField = this.name.replace(/group/, 'value');
    $('body').css('cursor', 'wait');
    $.ajax({
      url: '<?php echo document::ilink('ajax/option_values.json'); ?>?option_group_id=' + $(this).val(),
      type: 'get',
      cache: true,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR.readyState + '\n' + textStatus + '\n' + errorThrown.message);
      },
      success: function(data) {
        $('select[name="'+ valueField +'"]').html('');
        if ($('select[name="'+ valueField +'"]').attr('disabled')) $('select[name="'+ valueField +'"]').removeAttr('disabled');
        if (data) {
          $.each(data, function(i, zone) {
            $('select[name="'+ valueField +'"]').append('<option value="'+ zone.id +'">'+ zone.name +'</option>');
          });
        } else {
          $('select[name="'+ valueField +'"]').attr('disabled', 'disabled');
        }
      },
      complete: function() {
        $('body').css('cursor', 'auto');
      }
    });
  });

  var new_option_i = 1;
  $('#table-options').on('click', '.add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td colspan="2"><?php echo functions::general_escape_js(functions::form_draw_option_groups_list('options[new_option_i][group_id]', '')); ?></td>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_select_field('options[new_option_i][value_id]', array(array('','')), '')); ?></td>'
               + '  <td class="text-center"><?php echo functions::general_escape_js(functions::form_draw_select_field('options[new_option_i][price_operator]', array('+','%','*'), '+')); ?></td>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_currency_field(settings::get('store_currency_code'), 'options[new_option_i]['. settings::get('store_currency_code') .']', 0)); ?></td>'
<?php
  foreach (array_keys(currency::$currencies) as $currency_code) {
    if ($currency_code == settings::get('store_currency_code')) continue;
?>
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_currency_field($currency_code, 'options[new_option_i]['. $currency_code. ']', '')); ?></td>'
<?php
  }
?>
               + '  <td style="white-space: nowrap; text-align: right;"><a class="move-up" href="#" title="<?php echo functions::general_escape_js(language::translate('text_move_up', 'Move up'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"')); ?></a> <a class="move-down" href="#" title="<?php echo functions::general_escape_js(language::translate('text_move_down', 'Move down'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"')); ?></a> <a class="remove" href="#" title="<?php echo functions::general_escape_js(language::translate('title_remove', 'Remove'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"')); ?></a></td>'
               + '</tr>';
    output = output.replace(/new_option_i/g, 'new_' + new_option_i);
    $('#table-options tbody').append(output);
    new_option_i++;
  });

// Quantity Unit

  $('select[name="quantity_unit_id"]').change(function(){
    if ($('option:selected', this).data('decimals') === undefined) return;

    var decimals = $('option:selected', this).data('decimals');

    var value = parseFloat($('input[name="quantity"]').val()).toFixed(decimals);
    $('input[name="quantity"]').val(value);

    $('input[name^="option_stock"][name$="[quantity]"]').each(function(){
      var value = parseFloat($(this).val()).toFixed(decimals);
      $(this).val(value);
    });
  }).trigger('change');

// Stock

  $('#table-stock').on('change keyup', 'input[name*="quantity"]', function() {
    if ($(this).closest('tbody').find('input[name$="[quantity]"]').length == 0) return;

    var total = 0;
    $(this).closest('tbody').find('input[name$="[quantity]"]').each(function() {
      total += parseFloat($(this).val());
    });

    $('input[name="quantity"]').val(total);
  });

  $('#table-stock').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });

  $('#table-stock').on('click', '.move-up, .move-down', function(e) {
    e.preventDefault();
    var row = $(this).closest('tr');

    if ($(this).is('.move-up') && $(row).prevAll().length > 1) {
      $(row).insertBefore($(row).prev());
    } else if ($(this).is('.move-down') && $(row).nextAll().length > 0) {
      $(row).insertAfter($(row).next());
    }
  });

// New Stock Option (Modal)

  var option_index = 2;
  $('body').on('click', '#new-stock-option .add', function(e) {
    e.preventDefault();
    var output = '<tr>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_option_groups_list('new_option[option_index][group_id]', '')); ?></td>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_select_field('new_option[option_index][value_id]', array(array('','')), '', 'disabled="disabled"')); ?></td>'
               + '  <td><a class="remove" href="#" title="<?php echo functions::general_escape_js(language::translate('title_remove', 'Remove'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"')); ?></a></td>'
               + '</tr>';
    output = output.replace(/option_index/g, 'new_' + option_index);
    $(this).closest('table').find('tbody').append(output);
    option_index++;
  });

  $('body').on('click', '#new-stock-option .remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });

  $('#new-stock-option').on('change', 'select[name^="new_option"][name$="[group_id]"]', function(){
    var valueField = this.name.replace(/group/, 'value');
    var modal = $(this).closest('#new-stock-option');
    $('body').css('cursor', 'wait');
    $.ajax({
      url: '<?php echo document::ilink('ajax/option_values.json'); ?>?option_group_id=' + $(this).val(),
      type: 'get',
      cache: true,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR.readyState + '\n' + textStatus + '\n' + errorThrown.message);
      },
      success: function(data) {
        $(modal).find('select[name="'+ valueField +'"]').html('');
        if ($(modal).find('select[name="'+ valueField +'"]').attr('disabled')) $(modal).find('select[name="'+ valueField +'"]').removeAttr('disabled');
        if (data) {
          $.each(data, function(i, zone) {
            $(modal).find('select[name="'+ valueField +'"]').append('<option value="'+ zone.id +'">'+ zone.name +'</option>');
          });
        } else {
          $(modal).find('select[name="'+ valueField +'"]').attr('disabled', 'disabled');
        }
      },
      complete: function() {
        $('body').css('cursor', 'auto');
      }
    });
  });

  var new_option_stock_i = 1;
  $('body').on('click', '#new-stock-option button[name="add_stock_option"]', function(e) {
    e.preventDefault();
    var modal = $(this).closest('#new-stock-option');
    var new_option_code = '';
    var new_option_name = '';
    var use_coma = false;
    var success = $(modal).find('select[name^="new_option"][name$="[group_id]"]').each(function(i, groupElement) {
      var groupElement = $(modal).find(groupElement);
      var valueElement = $(modal).find('select[name="'+ $(groupElement).attr('name').replace(/group_id/g, 'value_id') +'"]');
      if (valueElement.val() == '') {
        alert("<?php echo language::translate('error_empty_option_group', 'Error: Empty option group'); ?>");
        return false;
      }
      if (groupElement.val() == '') {
        alert("<?php echo language::translate('error_empty_option_value', 'Error: Empty option value'); ?>");
        return false;
      }
      if (use_coma) {
        new_option_code += ',';
        new_option_name += ', ';
      }
      new_option_code += $(groupElement).val() + '-' + $(valueElement).val();
      new_option_name += $(valueElement).find('option:selected').text();
      use_coma = true;
    });
    if (new_option_code == '') return;
    var output = '<tr>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('options_stock[new_option_stock_i][id]', '') . functions::form_draw_hidden_field('options_stock[new_option_stock_i][combination]', 'new_option_code') . functions::form_draw_hidden_field('options_stock[new_option_stock_i][name]['. language::$selected['code'] .']', 'new_option_name')); ?>new_option_name</td>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_text_field('options_stock[new_option_stock_i][sku]', '')); ?></td>'
               + '  <td>'
               + '    <div class="input-group">'
               + '      <?php echo functions::general_escape_js(functions::form_draw_decimal_field('options_stock[new_option_stock_i][weight]', '0.00', 4, 0)); ?>'
               + '      <span class="input-group-addon">'
               + '        <?php echo functions::general_escape_js(functions::form_draw_weight_classes_list('options_stock[new_option_stock_i][weight_class]', '', false, 'style="width: auto;"')); ?>'
               + '      </span>'
               + '    </div>'
               + '  </td>'
               + '  <td>'
               + '    <div class="input-group">'
               + '      <?php echo functions::general_escape_js(functions::form_draw_decimal_field('options_stock[new_option_stock_i][dim_x]', '0.00', 4, 0)); ?>'
               + '      <?php echo functions::general_escape_js(functions::form_draw_decimal_field('options_stock[new_option_stock_i][dim_y]', '0.00', 4, 0)); ?>'
               + '      <?php echo functions::general_escape_js(functions::form_draw_decimal_field('options_stock[new_option_stock_i][dim_z]', '0.00', 4, 0)); ?>'
               + '      <span class="input-group-addon">'
               + '        <?php echo functions::general_escape_js(functions::form_draw_length_classes_list('options_stock[new_option_stock_i][dim_class]', '', false, 'style="width: auto;"')); ?>'
               + '      </span>'
               + '    </div>'
               + '  </td>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_decimal_field('options_stock[new_option_stock_i][quantity]', '0')); ?></td>'
               + '  <td class="text-right">'
               + '    <a class="move-up" href="#" title="<?php echo functions::general_escape_js(language::translate('text_move_up', 'Move up'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-arrow-circle-up fa-lg', 'style="color: #3399cc;"')); ?></a>'
               + '    <a class="move-down" href="#" title="<?php echo functions::general_escape_js(language::translate('text_move_down', 'Move down'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-arrow-circle-down fa-lg', 'style="color: #3399cc;"')); ?></a>'
               + '    <a class="remove" href="#" title="<?php echo functions::general_escape_js(language::translate('title_remove', 'Remove'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"')); ?></a>'
               + '  </td>'
               + '</tr>';
    while ($('input[name="options_stock[new_'+new_option_stock_i+']"]').length) new_option_stock_i++;
    output = output.replace(/new_option_stock_i/g, 'new_' + new_option_stock_i);
    output = output.replace(/new_option_code/g, new_option_code);
    output = output.replace(/new_option_name/g, new_option_name);
    $('#table-stock').find('tbody').append(output);
    new_option_stock_i++;
    $.featherlight.close();
  });

      var image_for_option_id = 0;
      $('#table-options').on('click', '.set_image_for_option',function(e) {
        image_for_option_id = $(this).data('option_id');
        return true;                  
      });
      $('body').on('click', 'a.set_image_id_for_option',function(e) {
        e.preventDefault;
        $('input#inp-option_image_'+image_for_option_id).val($(this).data('image-id'));
        $('input#inp-option_image_'+image_for_option_id).parent().find('img').attr('src', $(this).find('img').attr('src'));        
        $.featherlight.close();                  
      });
      
</script>