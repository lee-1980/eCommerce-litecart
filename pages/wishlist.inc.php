<?php
if (empty($_GET['page'])) $_GET['page'] = 1;

document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('wishlist') .'" />';
document::$snippets['title'][] = language::translate('wishlist:head_title', 'Wishlist');
document::$snippets['description'] = language::translate('wishlist:meta_description', '');


breadcrumbs::add(language::translate('title_wishlist', 'Wishlist'));

functions::draw_lightbox();
$box_wishlist_cache_token = cache::token('box_wishlist', array('basename', 'get', 'language', 'currency', 'account', 'prices'), 'file', 3600);
if (cache::capture($box_wishlist_cache_token)) {
$page = new ent_view();

$page->snippets['products'] = array();

$items_per_page = settings::get('items_per_page');

$sql_where = "";

if(!empty(customer::$data['id'])){
    $wishlist_query = database::query(
        "select * from ". DB_TABLE_WISHLIST ." where  customer_id = '" . (int)customer::$data['id'] ."';"
    );
    $wishlists = array();
    while($wishlist = database::fetch($wishlist_query)){
        array_push($wishlists, $wishlist['product_id']);
    }
    $sql_where = count($wishlists) > 0 ? "p.id IN (".implode(',', $wishlists).")":"p.id IN (-1)";
}
else{
    $sql_where = isset(session::$data['wishlist'])&&is_array(session::$data['wishlist'])&&count(session::$data['wishlist']) > 0?"p.id IN (".implode(',', session::$data['wishlist']).")":"p.id IN (-1)";
}


$products_query = functions::catalog_products_query(
    array(
        'sort' => 'price',
        'sql_where' => $sql_where,
    )
);

if (database::num_rows($products_query)) {
    if ($_GET['page'] > 1) database::seek($products_query, $items_per_page * ($_GET['page'] - 1));

    $page_items = 0;
    while ($listing_product = database::fetch($products_query)) {
        $listing_product['listing_type'] = 'column';
        $page->snippets['products'][] = $listing_product;
        if (++$page_items == $items_per_page) break;
    }
}

$page->snippets['pagination'] = functions::draw_pagination(ceil(database::num_rows($products_query) / $items_per_page));

echo $page->stitch('pages/wishlist');

    cache::end_capture($box_wishlist_cache_token);
}