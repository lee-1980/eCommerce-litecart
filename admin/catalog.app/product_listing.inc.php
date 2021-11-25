<?php error_reporting(0); ?>

<?php
$_pge = empty($_GET['page']) || !is_numeric($_GET['page']) ? 1 : (int)$_GET['page'];
$_query = !isset($_GET['query']) || $_GET['query']==='' ? null : $_GET['query'];
$_category_id = !isset($_GET['category_id']) || $_GET['category_id']==='' ? null : (int)$_GET['category_id'];
$_manufacturer_id = !isset($_GET['manufacturer_id']) || $_GET['manufacturer_id']==='' ? null : (int)$_GET['manufacturer_id'];
$_supplier_id = !isset($_GET['supplier_id']) || $_GET['supplier_id']==='' ? null : (int)$_GET['supplier_id'];
$_status = !isset($_GET['status']) || $_GET['status']==='' ? null : (int)$_GET['status'];
$_delivery_status = !isset($_GET['delivery_status_id']) || $_GET['delivery_status_id']==='' ? null : (int)$_GET['delivery_status_id'];

// product_action
if (isset($_POST['product_action']) && !empty($_POST['products'])) {

    try {

        switch ($_POST['product_action']){
            case 'enable':
                $delivery_status_query = database::query(
                    "update ". DB_TABLE_PRODUCTS ." set `status` = 1
                    where `id` in (". implode(',', array_map('intVal', $_POST['products'])) .")"
                );
                break;

            case 'disable':
                $delivery_status_query = database::query(
                    "update ". DB_TABLE_PRODUCTS ." set `status` = 0
                    where `id` in (". implode(',', array_map('intVal', $_POST['products'])) .")"
                );
                break;

            case 'delete':
                foreach (array_map('intVal', $_POST['products']) as $product_id) {
                    $product = new ctrl_product($product_id);
                    $product->delete();
                }
                break;
        }

        notices::add('success', language::translate('success_changes_saved', 'Changes saved successfully'));
        header('Location: '. document::link());
        exit;

    } catch (Exception $e) {
        notices::add('errors', $e->getMessage());
    }
}

// save
if(isset($_POST['save'])){
    foreach (array_map('intVal', $_POST['quantity']) as $product_id => $quantity) {

        $product = new ctrl_product((int)$product_id);
        foreach ($_POST['price'] as $currency_code => $prices) {
            $product->data['prices'][$currency_code] = (float)$prices[$product_id];
        }
        $product->data['quantity'] = (int)$quantity;
        try{
            if((int)$quantity<(int)$_POST['max_qty'][$product_id]) { throw new Exception(language::translate('error_must_be_less', 'Max Quantity must be less than Quantity'));}
        }
        catch (Exception $e) {
            notices::add('errors', $e->getMessage());
        }
        $product->data['max_qty'] = (int)$_POST['max_qty'][$product_id];
        $product->data['delivery_status_id'] = (int)$_POST['delivery_status_id'][$product_id];
        $product->data['status'] = isset($_POST['status'][$product_id]) ? (int)$_POST['status'][$product_id] : 0;
        $product->save();
    }
}

// query input
$query_input = functions::form_draw_search_field('query', true,
  'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') .'"
   onkeydown=" if (event.keyCode == 13) location=(\''. document::link('', array(), true, array('page', 'query')) .'&query=\' + encodeURIComponent(this.value))"'
);

// category_input
$category_input = form_draw_categories_list('category_id', $_category_id);

// manufacturer_input
$manufacturer_input = form_draw_manufacturers_list('manufacturer_id', $_manufacturer_id);

// supplier_input
$supplier_input = form_draw_suppliers_list('supplier_id', $_supplier_id);

// $product_status_input
$status_input = functions::form_draw_select_field('status', array(
  array('-- '. language::translate('title_status', 'Status') .' --', ''),
  array(language::translate('title_enabled', 'Enabled'),'1'),
  array(language::translate('title_disabled', 'Disabled'),'0'),
), $_status);

// $delivery_status_input
$delivery_status_query = database::query(
  "select ds.id, dsi.name , dsi.description from ". DB_TABLE_DELIVERY_STATUSES ." ds
      left join ". DB_TABLE_DELIVERY_STATUSES_INFO ." dsi on (dsi.delivery_status_id = ds.id and dsi.language_code = '". database::input(language::$selected['code']) ."')
      order by dsi.name asc;"
);

$delivery_status_options = array(array('-- '. language::translate('title_delivery_status', 'Delivery Status') . ' --', ''));

while ($row = database::fetch($delivery_status_query)) {
  $delivery_status_options[] = array($row['name'], $row['id'], 'title="'. htmlspecialchars($row['description']) .'"');
}

$delivery_status_input = functions::form_draw_select_field('delivery_status_id', $delivery_status_options, $_delivery_status);

// $products_query
$where = array();

if($_query !== null){

    $code_regex = functions::format_regex_code($_GET['query']);

    $where[] = "p.id = '". database::input($_GET['query']) ."'
      or pi.name like '%". database::input($_GET['query']) ."%'
      or p.code regexp '". database::input($code_regex) ."'
      or p.sku regexp '". database::input($code_regex) ."'
      or p.mpn regexp '". database::input($code_regex) ."'
      or p.gtin regexp '". database::input($code_regex) ."'
      or pi.short_description like '%". database::input($_GET['query']) ."%'
      or pi.description like '%". database::input($_GET['query']) ."%'
      or pi.name like '%". database::input($_GET['query']) ."%'";
}

if($_category_id !== null){
    $where[] = "p2c.category_id = '". database::input($_category_id) ."'";
}

if($_manufacturer_id !== null){
    $where[] = "p.manufacturer_id = '". database::input($_manufacturer_id) ."'";
}

if($_supplier_id !== null){
    $where[] = "p.supplier_id = '". database::input($_supplier_id) ."'";
}

if($_status !== null){
    $where[] = "p.status = '". database::input($_status) ."'";
}

if($_delivery_status !== null){
    $where[] = "p.delivery_status_id = '". database::input($_delivery_status) ."'";
}

switch($_GET['sort']) {
    case 'wishlist_desc':
        $sql_sort = "wishlist_customer desc , p.id asc";
        break;
    case 'wishlist_asc':
        $sql_sort = "wishlist_customer asc , p.id asc";
        break;
    case 'sku_desc':
        $sql_sort = "sku desc , p.id asc";
        break;
    case 'sku_asc':
        $sql_sort = "sku asc , p.id asc";
        break;        
    default:
        $sql_sort = "p.id asc";
        break;
}

$products_query = database::query(
    "select p.id, ci.name as default_category, m.name as manufacturer, s.name as supplier, p.status, p.delivery_status_id, p.sku, pr.". settings::get('store_currency_code') ." as price, count(distinct pwl.customer_id) as wishlist_customer ,p.quantity, pi.name, p.max_qty from ". DB_TABLE_PRODUCTS ." p
        left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
        left join ". DB_TABLE_PRODUCTS_TO_CATEGORIES ." p2c on (p2c.product_id = p.id)
        left join ". DB_TABLE_PRODUCTS_PRICES ." pr on (pr.product_id = p.id)
        left join ". DB_TABLE_WISHLIST ." pwl on (pwl.product_id = p.id)
        left join ". DB_TABLE_CATEGORIES_INFO ." ci on (ci.category_id = p.default_category_id and ci.language_code = '". language::$selected['code'] ."')
        left join ". DB_TABLE_MANUFACTURERS ." m on m.id = p.manufacturer_id
        left join ". DB_TABLE_SUPPLIERS ." s on s.id = p.supplier_id
        ". (!empty($where) ? " where (". implode(' and ', $where) .")" : "") ."
        group by p.id
        order by $sql_sort;"
);
?>

<?php echo functions::form_draw_form_begin('search_form', 'get') . functions::form_draw_hidden_field('app', true) . functions::form_draw_hidden_field('doc', true); ?>
<ul class="list-inline pull-right">
    <li><?php echo $query_input; ?></li>
    <li><?php echo $category_input; ?></li>
    <li><?php echo $manufacturer_input; ?></li>
    <li><?php echo $supplier_input; ?></li>
    <li><?php echo $delivery_status_input; ?></li>
    <li><?php echo $status_input; ?></li>
    <li><?php echo functions::form_draw_link_button(document::link('', array('app' => $_GET['app'], 'doc'=> 'edit_product')), language::translate('title_add_new_product', 'Add New Product'), '', 'add'); ?></li>
</ul>
<?php echo functions::form_draw_form_end(); ?>

<h1><?php echo $app_icon; ?> <?php echo language::translate('title_products', 'Products'); ?></h1>

<?php echo functions::form_draw_form_begin('products_form', 'post'); ?>

<table id="product_listing" class="table table-striped table-hover table-sortable data-table">
    <thead>
    <tr>
        <th width="44"><?php echo functions::draw_fonticon('fa-check-square-o fa-fw id-checkbox-toggle'); ?></th>
        <th class="text-right"><?php echo language::translate('title_id', 'ID'); ?></th>
        <th><?php echo language::translate('title_name', 'Name'); ?></th>
        
        <th data-sort="<?php echo $_GET['sort']==='wishlist_desc'?'wishlist_asc' : 'wishlist_desc'; ?>" class="text-center" ><?php echo language::translate('title_wishlist_customers', 'WishList_Customers'); ?></th>
        
        <th data-sort="<?php echo $_GET['sort']==='sku_desc'?'sku_asc' : 'sku_desc'; ?>" class="text-center" ><?php echo language::translate('title_sku', 'SKU'); ?></th>
        <th class="text-right" width="180"><?php echo language::translate('title_price', 'Price') . ' [' . settings::get('store_currency_code') . ']'; ?></th>
        <th class="text-center" width="140"><?php echo language::translate('title_max_qty', 'Max Quantity'); ?></th>
        <th class="text-center" width="140"><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
        <th class="text-center"><?php echo language::translate('title_delivery_status', 'Delivery Status'); ?></th>
        <th class="text-center"><?php echo language::translate('title_status', 'Status'); ?></th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (database::num_rows($products_query) > 0) {

      if ($_pge > 1) database::seek($products_query, (settings::get('data_table_rows_per_page') * ($_pge-1)));

      $product_items = 0;
      while ($product = database::fetch($products_query)) {
        ?>
          <tr>
              <td class="id_chk"><?php echo functions::form_draw_checkbox('products['. $product['id'] .']', $product['id']); ?></td>
              <td class="id"><?php echo $product['id']; ?></td>
              <td class="name">
                  <a href="<?php echo document::href_link('', array('doc' => 'edit_product', 'product_id' => $product['id']), true); ?>"><?php echo $product['name']; ?></a>
                  <br /><small>
                  <?php echo empty($product['default_category']) ? '-' : $product['default_category']; ?> |
                  <?php echo empty($product['manufacturer']) ? '-' : $product['manufacturer']; ?> |
                  <?php echo empty($product['supplier']) ? '-' : $product['supplier']; ?>
                  </small>
              </td>
              <th class="text-center"><?php echo $product['wishlist_customer'] ?></td>
              <td><?php echo $product['sku'] ?></td>
              <td><?php echo functions::form_draw_decimal_field('price['. settings::get('store_currency_code') .']['. $product['id'] .']', $product['price'], currency::$currencies[settings::get('store_currency_code')]['decimals'], 0, null, 'placeholder=""'); ?></td>
              <td class="stock"><?php echo form_draw_decimal_field('max_qty['. $product['id'] .']', $product['max_qty'], 0); ?></td>
              <td class="stock"><?php echo form_draw_decimal_field('quantity['. $product['id'] .']', $product['quantity'], 0); ?></td>
              <td class="delivery_status"><?php echo functions::form_draw_delivery_statuses_list('delivery_status_id['. $product['id'] .']', $product['delivery_status_id']); ?></td>
              <td class="status"><?php echo functions::form_draw_checkbox('status['. $product['id'] .']', 1, null, (int)$product['status']===1?'checked="checked"':null); ?></td>
              <td class="action"><a href="<?php echo document::href_link('', array('doc' => 'edit_product', 'product_id' => $product['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
          </tr>
        <?php
        if (++$product_items == settings::get('data_table_rows_per_page')) { break; }
      }
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="5"><?php echo language::translate('title_products', 'Products'); ?>: <?php echo database::num_rows($products_query); ?></td>
    </tr>
</table>

<div class="btn-group">
    <div class="pull-left">
    <?php echo form_draw_select_field('product_action', array(
        array('-- '. language::translate('text_with_selected', 'With selected') .' --', ''),
        array(language::translate('title_enable', 'Enable'),'enable'),
        array(language::translate('title_disable', 'Disable'),'disable'),
        array(language::translate('title_delete', 'Delete'),'delete'),
    ), null, false, 'disabled="disabled"'); ?>
    </div>
    <?php echo form_draw_button('save', language::translate('title_save', 'Save'), 'submit', 'style="margin-left:15px"', 'save'); ?>
</div>

<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::draw_pagination(ceil(database::num_rows($products_query)/settings::get('data_table_rows_per_page'))); ?>

<style>
    #product_listing tbody td {
        vertical-align: middle;
    }
    #product_listing tbody td.id {
        text-align: right;
    }
    #product_listing tbody td.stock input,
    #product_listing tbody td.delivery_status,
    #product_listing tbody td.status,
    #product_listing tbody td.action {
        text-align: center;
    }
    #product_listing tbody td.name {
        white-space: normal;
    }
</style>

<script>
    $('select[name="category_id"] option[value=""]').text('-- <?php echo language::translate('title_categories', 'Categories'); ?> --');
    $('select[name="manufacturer_id"] option[value=""]').text('-- <?php echo language::translate('title_manufacturer', 'Manufacturer'); ?> --');
    $('select[name="supplier_id"] option[value=""]').text('-- <?php echo language::translate('title_supplier', 'Supplier'); ?> --');

    var reload_selected = function(){
        var select = $('select[name="product_action"]');
        var selected = $("#product_listing tbody").find(".id_chk :checkbox:checked").length;
        select.find('option[value=""]').text('-- <?php echo language::translate('text_with_selected', 'With selected'); ?> ('+ selected +') --');
        if(selected > 0) { select.removeAttr('disabled'); } else {
            select.val('').attr('disabled', 'disabled');
        }
    };

    reload_selected();

    $("body")
        .on("click",'#product_listing thead .id-checkbox-toggle',function(){
            return $(this).closest("#product_listing").find("tbody .id_chk :checkbox").each(function(){
                $(this).prop("checked",!$(this).prop("checked"));
                reload_selected();
            })
        })
        .on("change", '#product_listing tbody .id_chk :checkbox', function(){
            reload_selected();
        });

    $('input[name="query"]').keypress(function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });

    $('form[name="search_form"] select').change(function(){
        $(this).closest('form').submit();
    });

    $('form[name="products_form"] .btn-group select').change(function(){
        $(this).closest('form').submit();
    });
</script>