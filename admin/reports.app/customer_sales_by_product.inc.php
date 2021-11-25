
<?php
  $_GET['date_from'] = !empty($_GET['date_from']) ? date('Y-m-d 00:00:00', strtotime($_GET['date_from'])) : null;
  $_GET['date_to'] = !empty($_GET['date_to']) ? date('Y-m-d 23:59:59', strtotime($_GET['date_to'])) : date('Y-m-d H:i:s');

  if ($_GET['date_from'] > $_GET['date_to']) list($_GET['date_from'], $_GET['date_to']) = array($_GET['date_to'], $_GET['date_from']);

  $date_first_order = database::fetch(database::query("select min(date_created) from ". DB_TABLE_ORDERS ." limit 1;"));
  $date_first_order = $date_first_order['min(date_created)'];
  if (empty($date_first_order)) $date_first_order = date('Y-m-d 00:00:00');
  if ($_GET['date_from'] < $date_first_order) $_GET['date_from'] = $date_first_order;

  if ($_GET['date_from'] > date('Y-m-d H:i:s')) $_GET['date_from'] = date('Y-m-d H:i:s');
  if ($_GET['date_to'] > date('Y-m-d H:i:s')) $_GET['date_to'] = date('Y-m-d H:i:s');
  if (!isset($_GET['order_status_id'])) $_GET['order_status_id'] = '';
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>

<div style="float: right; display: inline;">
  <?php echo functions::form_draw_form_begin('filter_form', 'get'); ?>
    <?php echo functions::form_draw_hidden_field('app'); ?>
    <?php echo functions::form_draw_hidden_field('doc'); ?>
    <ul class="list-inline">
      <li>
        <label><?php echo language::translate('title_sku', 'SKU'); ?></label>
        <?php echo functions::form_draw_text_field('sku', true); ?>
      </li>
      <li>
        <div class="form-group">
          <label><?php echo language::translate('title_date_period', 'Date Period'); ?></label>
          <div class="input-group">
            <?php echo functions::form_draw_month_field('date_from'); ?>
            <span class="input-group-addon">-</span>
            <?php echo functions::form_draw_month_field('date_to'); ?>
          </div>
        </div>
      </li>
      <li><?php echo functions::form_draw_button('filter', language::translate('title_filter_now', 'Filter')); ?></li>
    </ul>
  <?php echo functions::form_draw_form_end(); ?>
</div>


<h1 style="margin-top: 0px;"><?php echo $app_icon; ?> <?php echo language::translate('title_customers_by_product', 'Customers By Product'); ?></h1>

<table class="table table-striped data-table">
  <thead>
    <tr>
      <th><?php echo language::translate('title_id', 'ID'); ?></th>
      <th><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
      <th class="main"><?php echo language::translate('title_customer_name', 'Customer Name'); ?></th>
      <th><?php echo language::translate('title_product', 'Product'); ?></th>
      <th><?php echo language::translate('title_sku', 'SKU'); ?></th>
      
      
      
      <th><?php echo language::translate('title_purchase_date', 'Purchase Date'); ?></th>
    </tr>
  </thead>
  <tbody>
<?php
  $order_statuses = array();
  $orders_status_query = database::query(
    "select id from ". DB_TABLE_ORDER_STATUSES ." where is_sale;"
  );
  while ($order_status = database::fetch($orders_status_query)) {
    $order_statuses[] = (int)$order_status['id'];
  }

  $order_items_query = database::query(
    "select
      oi.quantity, oi.name, oi.sku,
      o.id as order_id, concat(o.customer_firstname, ' ', o.customer_lastname) as customer_name, o.date_created as order_date_created
    from ". DB_TABLE_ORDERS_ITEMS ." oi
    left join ". DB_TABLE_ORDERS ." o on (o.id = oi.order_id)
    left join ". DB_TABLE_ORDER_STATUSES ." os on (os.id = o.order_status_id)
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
    where o.order_status_id in ('". implode("', '", $order_statuses) ."')
    ". (!empty($_GET['sku']) ? "and oi.sku like '". database::input($_GET['sku']) ."'" : null) ."
    ". (!empty($_GET['order_status_id']) ? "and o.order_status_id = '". (int)$_GET['order_status_id'] ."'" : "and (os.is_archived is null or os.is_archived = 0)") ."
    and o.date_created >= '". date('Y-m-d H:i:s', mktime(0, 0, 0, date('m', strtotime($_GET['date_from'])), date('d', strtotime($_GET['date_from'])), date('Y', strtotime($_GET['date_from'])))) ."'
    and o.date_created <= '". date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', strtotime($_GET['date_to'])), date('d', strtotime($_GET['date_to'])), date('Y', strtotime($_GET['date_to'])))) ."'
    order by oi.name asc, customer_name asc;"
  );

  $orders_query = database::query(
    "select o.*, os.color as order_status_color, os.icon as order_status_icon, osi.name as order_status_name from ". DB_TABLE_ORDERS ." o
    left join ". DB_TABLE_ORDER_STATUSES ." os on (os.id = o.order_status_id)
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
    where o.order_status_id
    and os.is_archived = 0 
    order by o.date_created desc, o.id desc
    limit 50;"
  );

  if ((database::num_rows($order_items_query) > 0) && (database::num_rows($orders_query) > 0)){
    if ($_GET['page'] > 1) database::seek($order_items_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;

    while (($order_item = database::fetch($order_items_query)) && ($order = database::fetch($orders_query))) {
        

?>
  <tr>
    <td><div style="text-align: center;"><?php echo $order_item['order_id']; ?></div></td>
    <td><div style="text-align: center;"><?php echo (float)$order_item['quantity']; ?></div></td>
    <td><a href="<?php echo document::link(null, array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => (float)$order_item['order_id']), false); ?>"><?php echo $order_item['customer_name']; ?></a></td>
    
    <td><?php echo $order_item['name']; ?></td>
    <td><?php echo $order_item['sku']; ?></td>
    <td><?php echo $order_item['order_date_created']; ?></td>
          <td>
        <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'printable_order_copy', 'order_id' => $order_item['order_id'], 'media' => 'print')); ?>" target="_blank" title="<?php echo language::translate('title_order_copy', 'Order Copy'); ?>"><?php echo functions::draw_fonticon('fa-print'); ?></a>
      </td>
  </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
  </tbody>
</table>

<?php echo functions::draw_pagination(ceil(database::num_rows($order_items_query)/settings::get('data_table_rows_per_page'))); ?>



<?php
  $_GET['date_from'] = !empty($_GET['date_from']) ? date('Y-m-d 00:00:00', strtotime($_GET['date_from'])) : null;
  $_GET['date_to'] = !empty($_GET['date_to']) ? date('Y-m-d 23:59:59', strtotime($_GET['date_to'])) : date('Y-m-d H:i:s');

  if ($_GET['date_from'] > $_GET['date_to']) list($_GET['date_from'], $_GET['date_to']) = array($_GET['date_to'], $_GET['date_from']);

  $date_first_order = database::fetch(database::query("select min(date_created) from ". DB_TABLE_ORDERS ." limit 1;"));
  $date_first_order = $date_first_order['min(date_created)'];
  if (empty($date_first_order)) $date_first_order = date('Y-m-d 00:00:00');
  if ($_GET['date_from'] < $date_first_order) $_GET['date_from'] = $date_first_order;

  if ($_GET['date_from'] > date('Y-m-d H:i:s')) $_GET['date_from'] = date('Y-m-d H:i:s');
  if ($_GET['date_to'] > date('Y-m-d H:i:s')) $_GET['date_to'] = date('Y-m-d H:i:s');
  if (!isset($_GET['order_status_id'])) $_GET['order_status_id'] = '';
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>

<div style="float: right; display: inline;">
  <?php echo functions::form_draw_form_begin('filter_form', 'get'); ?>
    <?php echo functions::form_draw_hidden_field('app'); ?>
    <?php echo functions::form_draw_hidden_field('doc'); ?>
    <ul class="list-inline">


</div>


<h1 style="margin-top: 0px;"><?php echo $app_icon; ?> <?php echo language::translate('title_total_item_sold', 'Total Item sold'); ?></h1>
<table class="table table-striped data-table">
  <thead>
    <tr>
      <th><?php echo language::translate('title_id', 'ID'); ?></th>
      <th><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
      <th class="main"><?php echo language::translate('title_customer_name', 'Customer Name'); ?></th>     
      <th><?php echo language::translate('title_product', 'Product'); ?></th>
      <th><?php echo language::translate('title_sku', 'SKU'); ?></th>
      <th><?php echo language::translate('title_purchase_date', 'Purchase Date'); ?></th>
    </tr>
  </thead>
  <tbody>
<?php
  $order_statuses = array();
  $orders_status_query = database::query(
    "select id from ". DB_TABLE_ORDER_STATUSES ." where is_sale;"
  );
  while ($order_status = database::fetch($orders_status_query)) {
    $order_statuses[] = (int)$order_status['id'];
  }

  $order_items_query = database::query(
    "select
      oi.quantity, oi.name, oi.sku,
      o.id as order_id, concat(o.customer_firstname, ' ', o.customer_lastname) as customer_name, o.date_created as order_date_created
    from ". DB_TABLE_ORDERS_ITEMS ." oi
    left join ". DB_TABLE_ORDERS ." o on (o.id = oi.order_id)
    left join ". DB_TABLE_ORDER_STATUSES ." os on (os.id = o.order_status_id)
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')
    where o.order_status_id in ('". implode("', '", $order_statuses) ."')
    ". (!empty($_GET['sku']) ? "and oi.sku like '". database::input($_GET['sku']) ."'" : null) ."
    and o.date_created >= '". date('Y-m-d H:i:s', mktime(0, 0, 0, date('m', strtotime($_GET['date_from'])), date('d', strtotime($_GET['date_from'])), date('Y', strtotime($_GET['date_from'])))) ."'
    and o.date_created <= '". date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', strtotime($_GET['date_to'])), date('d', strtotime($_GET['date_to'])), date('Y', strtotime($_GET['date_to'])))) ."'
    order by oi.name asc, customer_name asc;"
  );



  if (database::num_rows($order_items_query) > 0){
    if ($_GET['page'] > 1) database::seek($order_items_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;

    while ($order_item = database::fetch($order_items_query)) {
        

?>
  <tr>
    <td><div style="text-align: center;"><?php echo $order_item['order_id']; ?></div></td>
    <td><div style="text-align: center;"><?php echo (float)$order_item['quantity']; ?></div></td>
    <td><a href="<?php echo document::link(null, array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => (float)$order_item['order_id']), false); ?>"><?php echo $order_item['customer_name']; ?></a></td>
    <td><?php echo $order_item['name']; ?></td>
    <td><?php echo $order_item['sku']; ?></td>
    <td><?php echo $order_item['order_date_created']; ?></td>
          <td>
        <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'printable_order_copy', 'order_id' => $order_item['order_id'], 'media' => 'print')); ?>" target="_blank" title="<?php echo language::translate('title_order_copy', 'Order Copy'); ?>"><?php echo functions::draw_fonticon('fa-print'); ?></a>
      </td>
  </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
  </tbody>
</table>

<?php echo functions::draw_pagination(ceil(database::num_rows($order_items_query)/settings::get('data_table_rows_per_page'))); ?>