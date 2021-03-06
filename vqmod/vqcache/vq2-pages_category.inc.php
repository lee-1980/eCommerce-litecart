<?php
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;
  
      if (empty($_GET['sort'])) $_GET['sort'] = 'popularity';
      
  if (empty($_GET['category_id'])) {
    header('Location: '. document::ilink('categories'));
    exit;
  }

  if (!empty($_GET['attributes'])) {
    $_GET['attributes'] = array_map('array_filter', $_GET['attributes']);
    $_GET['attributes'] = array_filter($_GET['attributes']);
  }

  $category = reference::category($_GET['category_id']);

  if (empty($category->id)) {
    http_response_code(410);
    echo language::translate('error_410_gone', 'The requested file is no longer available');
    return;
  }

  if (empty($category->status)) {
    http_response_code(404);
    echo language::translate('error_404_not_found', 'The requested file could not be found');
    return;
  }

  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('category', array('category_id' => $category->id), false) .'" />';
  document::$snippets['title'][] = $category->head_title ? $category->head_title : $category->name;
  document::$snippets['description'] = $category->meta_description ? $category->meta_description : strip_tags($category->short_description);

  
      breadcrumbs::add(language::translate('title_categories', 'Categories'));
      
  foreach (array_slice(functions::catalog_category_trail($category->id), 0, -1, true) as $category_id => $category_name) {
    breadcrumbs::add($category_name, document::ilink('category', array('category_id' => $category_id)));
  }
  breadcrumbs::add($category->name);

  functions::draw_lightbox();

  $_page = new ent_view();

  $box_category_cache_token = cache::token('box_category', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file');
  if (!$_page->snippets = cache::get($box_category_cache_token, ($_GET['sort'] == 'popularity') ? 0 : 3600)) {

    $_page->snippets = array(
      'id' => $category->id,
      'name' => $category->name,
      'short_description' => $category->short_description,
      'description' => $category->description,
      'h1_title' => $category->h1_title ? $category->h1_title : $category->name,
      'head_title' => $category->head_title ? $category->head_title : $category->name,
      'meta_description' => $category->meta_description ? $category->meta_description : $category->short_description,
      'image' => $category->image ? 'images/' . $category->image : '',
      'subcategories' => array(),
      'products' => array(),
      'sort_alternatives' => array(
        'name' => language::translate('title_name', 'Name'),
        'price' => language::translate('title_price', 'Price'),
        'popularity' => language::translate('title_popularity', 'Popularity'),
        'date' => language::translate('title_date', 'Date'),
      ),
    );

  // Subcategories
    $subcategories_query = functions::catalog_categories_query($category->id);
    if (database::num_rows($subcategories_query)) {
      while ($subcategory = database::fetch($subcategories_query)) {
        $_page->snippets['subcategories'][] = $subcategory;
      }
    }

  // Products
    switch ($category->list_style) {
      case 'rows':
      case 'columns':
      default:
        $items_per_page = settings::get('items_per_page');
        break;
    }

    $products_query = functions::catalog_products_query(array(
      'categories' => array($category->id),
      'manufacturers' => !empty($_GET['manufacturers']) ? $_GET['manufacturers'] : null,
      'attributes' => !empty($_GET['attributes']) ? $_GET['attributes'] : null,
      'sort' => $_GET['sort'],
      
      'campaigns_first' => false,
      
    ));

    if (database::num_rows($products_query)) {
      if ($_GET['page'] > 1) database::seek($products_query, $items_per_page * ($_GET['page'] - 1));

      $page_items = 0;
      while ($listing_product = database::fetch($products_query)) {
        switch($category->list_style) {
          case 'rows':
            $listing_product['listing_type'] = 'column';
            $_page->snippets['products'][] = $listing_product;
            break;
          default:
          case 'columns':
            $listing_product['listing_type'] = 'column';
            $_page->snippets['products'][] = $listing_product;
            break;
        }
        if (++$page_items == $items_per_page) break;
      }
    }

    $_page->snippets['num_products_page'] = count($_page->snippets['products']);
    $_page->snippets['num_products_total'] = (int)database::num_rows($products_query);
    $_page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($products_query)/$items_per_page));

    cache::set($box_category_cache_token, $_page->snippets);
  }

  echo $_page->stitch('pages/category');
