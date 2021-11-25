<?php

  return $app_config = array(
    'name' => language::translate('title_catalog', 'Catalog'),
    'default' => 'catalog',
    'priority' => 0,
    'theme' => array(
      'color' => '#d0cb2b',
      'icon' => 'fa-th',
    ),
    'menu' => array(
      array(
        'title' => language::translate('title_catalog', 'Catalog'),
        'doc' => 'catalog',
        'params' => array(),
      ),
array(
        'title' => language::translate('title_products', 'Products'),
        'doc' => 'product_listing',
        'params' => array(),
      ),

      array(
        'title' => language::translate('title_listing_links', 'Listing Links'),
        'doc' => 'listing_links',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_sign_in_date_prices', 'Sign In Date'),
        'doc' => 'sign_in_date_prices',
        'params' => array(),
      ),  
      array(
        'title' => language::translate('title_fake_sold_out_date_prices', 'Fake Sold Out Date'),
        'doc' => 'fake_sold_out_date_prices',
        'params' => array(),
      ),       
      array(
        'title' => language::translate('title_default_prices', 'Default Prices'),
        'doc' => 'default_prices',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_guest_prices', 'Guest Prices'),
        'doc' => 'guest_prices',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_wholesale_prices', 'Wholesale Prices'),
        'doc' => 'wholesale_prices',
        'params' => array(),
      ),  
      array(
        'title' => language::translate('title_vip_prices', 'VIP Prices'),
        'doc' => 'vip_prices',
        'params' => array(),
      ),      
      array(
        'title' => language::translate('title_switches', 'Switches'),
        'doc' => 'switches',
        'params' => array(),
      ),         
      array(
        'title' => language::translate('title_switches_checkbox', 'Switches Checkbox'),
        'doc' => 'checkbox',
        'params' => array(),
      ),       
      
      array(
        'title' => language::translate('title_attribute_groups', 'Attribute Groups'),
        'doc' => 'attribute_groups',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_option_groups', 'Option Groups'),
        'doc' => 'option_groups',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_manufacturers', 'Manufacturers'),
        'doc' => 'manufacturers',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_suppliers', 'Suppliers'),
        'doc' => 'suppliers',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_delivery_statuses', 'Delivery Statuses'),
        'doc' => 'delivery_statuses',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_sold_out_statuses', 'Sold Out Statuses'),
        'doc' => 'sold_out_statuses',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_quantity_units', 'Quantity Units'),
        'doc' => 'quantity_units',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_csv_import_export', 'CSV Import/Export'),
        'doc' => 'csv',
        'params' => array(),
      ),

    // BOF: Multi Update Products
      array(
        'title' => language::translate('title_multi_update', 'Multi Update'),
        'doc' => 'multi_update',
        'params' => array(),
      ),
    // EOF: Multi Update Products
      
    ),
    'docs' => array(
      'attribute_groups' => 'attribute_groups.inc.php',
      'attribute_values.json' => 'attribute_values.json.inc.php',
      'catalog' => 'catalog.inc.php',
'product_listing' => 'product_listing.inc.php',
      'edit_attribute_group' => 'edit_attribute_group.inc.php',
      'edit_product' => 'edit_product.inc.php',
      'edit_category' => 'edit_category.inc.php',
      'option_groups' => 'option_groups.inc.php',
      'edit_option_group' => 'edit_option_group.inc.php',
      'manufacturers' => 'manufacturers.inc.php',
      'edit_manufacturer' => 'edit_manufacturer.inc.php',
      'suppliers' => 'suppliers.inc.php',
      'edit_supplier' => 'edit_supplier.inc.php',
      'delivery_statuses' => 'delivery_statuses.inc.php',
      'edit_delivery_status' => 'edit_delivery_status.inc.php',
      'sold_out_statuses' => 'sold_out_statuses.inc.php',
      'edit_sold_out_status' => 'edit_sold_out_status.inc.php',
      'quantity_units' => 'quantity_units.inc.php',
      'edit_quantity_unit' => 'edit_quantity_unit.inc.php',

      'listing_links' => 'listing_links.inc.php',
      'edit_listing_link' => 'edit_listing_link.inc.php',
            
      'sign_in_date_prices' => 'sign_in_date_prices.inc.php',
      'edit_sign_in_date_price' => 'edit_sign_in_date_price.inc.php',            

      'fake_sold_out_date_prices' => 'fake_sold_out_date_prices.inc.php',
      'edit_fake_sold_out_date_price' => 'edit_fake_sold_out_date_price.inc.php',  
            
      'default_prices' => 'default_prices.inc.php',
      'edit_default_price' => 'edit_default_price.inc.php',
      
      'guest_prices' => 'guest_prices.inc.php',
      'edit_guest_price' => 'edit_guest_price.inc.php',
      
      'wholesale_prices' => 'wholesale_prices.inc.php',
      'edit_wholesale_price' => 'edit_wholesale_price.inc.php',   
      
      'vip_prices' => 'vip_prices.inc.php',
      'edit_vip_price' => 'edit_vip_price.inc.php',       
      
      'switches' => 'switches.inc.php',
      'switches' => 'switches.inc.php',       

      'checkbox' => 'checkbox.inc.php',
      'checkbox' => 'checkbox.inc.php',       
      
      
      'csv' => 'csv.inc.php',

  // BOF: Multi Update Products
    'multi_update' => 'multi_update.inc.php',
  // EOF: Multi Update Products
      
      'products.json' => 'products.json.inc.php',
    ),
  );
