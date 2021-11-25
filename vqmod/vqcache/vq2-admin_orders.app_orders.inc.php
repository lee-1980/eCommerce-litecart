<?php

  $_GET['date_from'] = !empty($_GET['date_from']) ? date('Y-m-d', strtotime($_GET['date_from'])) : null;
  $_GET['date_to'] = !empty($_GET['date_to']) ? date('Y-m-d', strtotime($_GET['date_to'])) : date('Y-m-d');

  if ($_GET['date_from'] > $_GET['date_to']) list($_GET['date_from'], $_GET['date_to']) = array($_GET['date_to'], $_GET['date_from']);

  $date_first_order = database::fetch(database::query("select min(date_created) from ". DB_TABLE_ORDERS ." limit 1;"));
  $date_first_order = date('Y-m-d', strtotime($date_first_order['min(date_created)']));
  if (empty($date_first_order)) $date_first_order = date('Y-m-d');
  if ($_GET['date_from'] < $date_first_order) $_GET['date_from'] = $date_first_order;

  if ($_GET['date_from'] > date('Y-m-d')) $_GET['date_from'] = date('Y-m-d');
  if ($_GET['date_to'] > date('Y-m-d')) $_GET['date_to'] = date('Y-m-d');

  if (!isset($_GET['order_status_id'])) $_GET['order_status_id'] = '';
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;
  if (empty($_GET['sort'])) $_GET['sort'] = 'date_created';

  if (isset($_POST['star']) || isset($_POST['unstar'])) {
    database::query(
      "update ". DB_TABLE_ORDERS ."
      set starred = ". (isset($_POST['star']) ? 1 : 0) ."
      where id = ". (int)$_POST['order_id'] ."
      limit 1;"
    );
    exit;
  }

  if (!empty($_POST['order_action'])) {

    try {
      if (empty($_POST['orders'])) throw new Exception(language::translate('error_must_select_orders', 'You must select orders to perform the operation'));

      list($module_id, $action_id) = explode(':', $_POST['order_action']);

      $order_action = new mod_order();

      $actions = $order_action->actions();

      if (!method_exists($order_action->modules[$module_id], $actions[$module_id]['actions'][$action_id]['function'])) {
        throw new Exception(language::translate('error_method_doesnt_exist', 'The method doesn\'t exist'));
      }

      sort($_POST['orders']);

      echo call_user_func(array($order_action->modules[$module_id], $actions[$module_id]['actions'][$action_id]['function']), $_POST['orders']);
      return;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (!empty($_POST['set_order_status'])) {
    try {
      if (empty($_POST['orders'])) throw new Exception(language::translate('error_must_select_orders', 'You must select orders to perform the operation'));
       foreach ($_POST['orders'] as $order_id) {
        $order = new ctrl_order($order_id);
        $order->data['order_status_id'] = $_POST['order_status_id'];
        $order->save();
      }
      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
    } catch(Exception $e) {
      notices::$data['errors'][] = $e->getMessage();
    }
  }
      

  $payment_options_query = database::query(
    "select distinct payment_option_name
    from ". DB_TABLE_ORDERS ." o
    where payment_option_name != ''
    order by payment_option_name asc"
  );

  $payment_options = array(array('-- '. language::translate('title_payment_method', 'Payment Method') .' --', ''));
  while ($payment_option = database::fetch($payment_options_query)) {
    $payment_options[] = array($payment_option['payment_option_name'], $payment_option['payment_option_name']);
  }

// Table Rows
  $orders = array();

  if (!empty($_GET['query'])) {
    $sql_where_query = array(

      "o.customer_phone like '%". database::input($_GET['query']) ."%'",
      
      "o.id = '". database::input($_GET['query']) ."'",
      "o.uid = '". database::input($_GET['query']) ."'",
      "o.customer_email like '%". database::input($_GET['query']) ."%'",
      "o.customer_tax_id like '%". database::input($_GET['query']) ."%'",
      "o.customer_company like '%". database::input($_GET['query']) ."%'",
      "concat(o.customer_firstname, ' ', o.customer_lastname) like '%". database::input($_GET['query']) ."%'",
      "o.payment_transaction_id like '". database::input($_GET['query']) ."'",
      "o.shipping_tracking_id like '". database::input($_GET['query']) ."'",
      "o.reference like '%". database::input($_GET['query']) ."%'",
      "o.id in (
        select order_id from ". DB_TABLE_ORDERS_ITEMS ."
        where name like '%". database::input($_GET['query']) ."%'
        or sku like '%". database::input($_GET['query']) ."%'
      )",
    );
  }

  switch($_GET['sort']) {
    case 'id':
      $sql_sort = "o.id desc";
      break;

        case 'grand_total_desc':
             $sql_sort = "grand_total desc";
             break;
        case 'grand_total_asc':
             $sql_sort = "grand_total asc";
             break;
        case 'payment_due_desc':
             $sql_sort = "o.payment_due desc";
             break;
        case 'payment_due_asc':
             $sql_sort = "o.payment_due asc";
             break;
      
    case 'country':
      $sql_sort = "o.customer_country_code";
      break;
    default:
      $sql_sort = "o.starred desc, o.date_created desc, o.date_created desc, o.id desc";
      break;
  }

  $orders_query = database::query(
    
        "select o.*, os.color as order_status_color, os.icon as order_status_icon, osi.name as order_status_name , (o.payment_due - if(ot.total_ot_customer_discounts, ot.total_ot_customer_discounts, 0)) as grand_total from ". DB_TABLE_ORDERS ." o	
      
    left join ". DB_TABLE_ORDER_STATUSES ." os on (os.id = o.order_status_id)
    left join ". DB_TABLE_ORDER_STATUSES_INFO ." osi on (osi.order_status_id = o.order_status_id and osi.language_code = '". language::$selected['code'] ."')

        left join (select order_id, sum(value) as total_ot_customer_discounts from ". DB_TABLE_ORDERS_TOTALS ." where module_id = 'ot_customer_payment' or module_id = 'ot_discount_code' group by order_id) ot on (ot.order_id = o.id)
      
    where o.id
    ". (!empty($sql_where_query) ? "and (". implode(" or ", $sql_where_query) .")" : "") ."
    ". (!empty($_GET['order_status_id']) ? "and o.order_status_id = ". (int)$_GET['order_status_id'] ."" : "and (os.is_archived is null or os.is_archived = 0 or unread = 1)") ."
    ". (!empty($_GET['payment_option_name']) ? "and o.payment_option_name = '". database::input($_GET['payment_option_name']) ."'" : '') ."
    ". (!empty($_GET['date_from']) ? "and o.date_created >= '". date('Y-m-d H:i:s', mktime(0, 0, 0, date('m', strtotime($_GET['date_from'])), date('d', strtotime($_GET['date_from'])), date('Y', strtotime($_GET['date_from'])))) ."'" : '') ."
    ". (!empty($_GET['date_to']) ? "and o.date_created <= '". date('Y-m-d H:i:s', mktime(23, 59, 59, date('m', strtotime($_GET['date_to'])), date('d', strtotime($_GET['date_to'])), date('Y', strtotime($_GET['date_to'])))) ."'" : '') ."
    order by $sql_sort;"
  );

  if ($_GET['page'] > 1) database::seek($orders_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

  $page_items = 0;
  while ($order = database::fetch($orders_query)) {

    if (empty($order['order_status_id'])) {
      $order['order_status_icon'] = 'fa-minus';
      $order['order_status_color'] = '#cccccc';
    }

    if (empty($order['order_status_icon'])) $order['order_status_icon'] = 'fa-circle-thin';
    if (empty($order['order_status_color'])) $order['order_status_color'] = '#cccccc';

      $has_order_history = database::num_rows(database::query(
        "select id from ". DB_TABLE_ORDERS ."
        where order_status_id
        and customer_email = '". database::input($order['customer_email']) ."'
        and (id != ". (int)$order['id'] ." and id < ". (int)$order['id'] .")
        limit 1;"
      ));
      

    $order['css_classes'] = array();
    if (empty($order['order_status_id'])) $order['css_classes'][]= 'semi-transparent';
    if (!empty($order['unread'])) $order['css_classes'][]= 'bold';

    $orders[] = $order;

    if (++$page_items == settings::get('data_table_rows_per_page')) break;
  }

// Number of Rows
  $num_rows = database::num_rows($orders_query);

// Pagination
  $num_pages = ceil($num_rows/settings::get('data_table_rows_per_page'));

// Actions
  $order_actions = array();

  $mod_order = new mod_order();
  if ($modules = $mod_order->actions()) {
    foreach ($modules as $module) {
      $order_actions[] = $module;
    }
  }

?>
<style>
table tr.bold {
  font-weight: bold;

        color: #3bed7d;	
      
}

table .fa-star-o:hover {
  transform: scale(1.5);
}
table .fa-star:hover {
  transform: scale(1.5);
}

#order-actions li {
  vertical-align: middle;
}
#order-actions li fieldset {
  border: 1px #ccc solid;
}
#order-actions li fieldset legend {
  color: #999;
}
</style>

<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_orders', 'Orders'); ?>
  </div>

  <div class="panel-action">
    <ul class="list-inline">
      <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('doc' => 'edit_order', 'redirect_url' => $_SERVER['REQUEST_URI']), true), language::translate('title_create_new_order', 'Create New Order'), '', 'add'); ?></li>
    </ul>
  </div>

  <?php echo functions::form_draw_form_begin('search_form', 'get'); ?>
    <?php echo functions::form_draw_hidden_field('app', true); ?>
    <?php echo functions::form_draw_hidden_field('doc', true); ?>
    <div class="panel-filter">
      <div class="expandable"><?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword').'"'); ?></div>
      <div><?php echo functions::form_draw_order_status_list('order_status_id', true, false); ?></div>
      <div><?php echo functions::form_draw_select_field('payment_option_name', $payment_options, true); ?></div>
      <div class="input-group" style="max-width: 380px;">
        <?php echo functions::form_draw_date_field('date_from', true); ?>
        <span class="input-group-addon"> - </span>
        <?php echo functions::form_draw_date_field('date_to', true); ?>
      </div>
      <div><?php echo functions::form_draw_button('filter', language::translate('title_search', 'Search'), 'submit'); ?></div>
    </div>
  <?php echo functions::form_draw_form_end(); ?>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('orders_form', 'post'); ?>

      <table class="table table-striped table-hover table-sortable data-table">
        <thead>
          <tr>
            <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle', 'data-toggle="checkbox-toggle"'); ?></th>
            <th>&nbsp;</th>
            <th data-sort="id"><?php echo language::translate('title_id', 'ID'); ?></th>

        <th>&nbsp;</th>
      
            <th>&nbsp;</th>
            <th data-sort="customer" class="main"><?php echo language::translate('title_customer_name', 'Customer Name'); ?></th>

		<th data-sort="customer" class="main"><?php echo language::translate('title_reference', 'Reference'); ?></th>	
		<th class="text-center" data-sort="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'payment_due_asc'?'payment_due_desc':'payment_due_asc';?>"><span style="color:#ff7e61"><?php echo language::translate('title_debit', 'Debit'); ?></span></th>		
        <th class="text-center" data-sort="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'payment_due_asc'?'payment_due_desc':'payment_due_asc';?>"><span style="color:#7f8fd4"><?php echo language::translate('title_credit', 'Credit'); ?></span></th>
      
            <th data-sort="country"><?php echo language::translate('title_country', 'Country'); ?></th>
            <th data-sort="payment_method"><?php echo language::translate('title_payment_method', 'Payment Method'); ?></th>

        <th data-sort="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'grand_total_asc'?'grand_total_desc':'grand_total_asc';?>"><?php echo language::translate('title_grand_total', 'Grand Total'); ?></th>	
      
            
        <!-- NONE -->	
      
            
        <!-- none -->
      
            <th data-sort="order_status" class="text-center"><?php echo language::translate('title_order_status', 'Order Status'); ?></th>
            <th data-sort="date_created"><?php echo language::translate('title_date', 'Date'); ?></th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($orders as $order) { ?>
          <tr class="<?php echo implode(' ', $order['css_classes']); ?>" data-id="<?php echo $order['id']; ?>">
            <td><?php echo functions::form_draw_checkbox('orders['.$order['id'].']', $order['id'], (isset($_POST['orders']) && in_array($order['id'], $_POST['orders'])) ? $order['id'] : false); ?></td>
            <td><?php echo functions::draw_fonticon($order['order_status_icon'].' fa-fw', 'style="color: '. $order['order_status_color'] .';"'); ?></td>
            <td><?php echo $order['id']; ?></td>

      <td><?php echo !empty($has_order_history) ? functions::draw_fonticon('fa-retweet', 'style="color: #ffaa00;" title="'. language::translate('title_recurred', 'Recurred') .'"') : ''; ?></td>
      
            <td><?php echo (!empty($order['starred'])) ? functions::draw_fonticon('fa-star', 'style="color: #f2b01e;"') : functions::draw_fonticon('fa-star-o', 'style="color: #ccc;"'); ?></td>
            
        <td><a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id'], 'redirect_url' => $_SERVER['REQUEST_URI'])); ?>"><div><span style="color:#7f8fd4"><strong><?php echo $order['customer_firstname'] .' '. $order['customer_lastname']; ?></strong>
        <span style="color:#30AD23"><?php echo empty($order['customer_id']) ? ' <em>('. language::translate('title_guest', 'Guest') .')</em>' : ''; ?></a>
	    <div><span style="color:#ffb83d"><?php echo $order['customer_email']; ?></span></br><span style="color:#7f8fd4"><?php echo $order['customer_phone'] ? $order['customer_phone'] : $order['customer_phone'] .' '. $order['customer_phone']; ?>
      

		<td><strong><span style="color:#fff"><?php echo $order['reference']; ?></strong></td>	
		<td class="text-right"><strong><span style="color:#ff7e61"><?php echo currency::format($order['payment_due'], false, $order['currency_code'], $order['currency_value']); ?></span></td>
		<th class="text-right"><span style="color:#7f8fd4"><?php echo currency::format(($order['grand_total']) - ($order['payment_due']), false, $order['currency_code'], $order['currency_value']); ?></span></th>
      
            <td><?php echo !empty($order['customer_country_code']) ? reference::country($order['customer_country_code'])->name : ''; ?></td>
            <td><?php echo $order['payment_option_name']; ?></td>

        <th class="text-right">
		<span style="color:#ffb83d"><?php echo currency::format($order['grand_total'], false, $order['currency_code'], $order['currency_value']); ?></span>
		</br>
		<span style="color:#7f8fd4">- <?php echo currency::format(($order['grand_total']) - ($order['payment_due']), false, $order['currency_code'], $order['currency_value']); ?></span>
		</br>
		<span style="color:#ff7e61"><?php echo currency::format($order['payment_due'], false, $order['currency_code'], $order['currency_value']); ?></span>
        </th>	
      
            
        <!-- NONE -->	
      
            
        <!-- none -->
      
            <td class="text-center"><?php echo ($order['order_status_id'] == 0) ? language::translate('title_unprocessed', 'Unprocessed') : $order['order_status_name']; ?></td>
            <td class="text-right"><?php echo language::strftime(language::$selected['format_datetime'], strtotime($order['date_created'])); ?></td>
            <td>
              <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'printable_packing_slip', 'order_id' => $order['id'], 'media' => 'print')); ?>" target="_blank" title="<?php echo language::translate('title_packing_slip', 'Packing Slip'); ?>"><?php echo functions::draw_fonticon('fa-file-text-o'); ?></a>
              <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'printable_order_copy', 'order_id' => $order['id'], 'media' => 'print')); ?>" target="_blank" title="<?php echo language::translate('title_order_copy', 'Order Copy'); ?>"><?php echo functions::draw_fonticon('fa-print'); ?></a>
              <a href="<?php echo document::href_link('', array('app' => 'orders', 'doc' => 'edit_order', 'order_id' => $order['id'], 'redirect_url' => $_SERVER['REQUEST_URI'])); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a>
            </td>
          </tr>
          <?php } ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="12"><?php echo language::translate('title_orders', 'Orders'); ?>: <?php echo $num_rows; ?></td>

    <td></td>
      
          </tr>
        </tfoot>
      </table>

      <p>
        <ul id="order-actions" class="list-inline">

      <li><fieldset>
        <legend><?php echo language::translate('title_order_status', 'Order Status'); ?></legend>
        <div class="input-group">
          <?php echo functions::form_draw_order_status_list('order_status_id', true); ?>
          <span class="input-group-btn">
            <button class="btn btn-default" name="set_order_status" value="true" type="submit" formtarget="_self"><?php echo language::translate('title_set', 'Set'); ?></button>
          </span>
        </div>
      </fieldset></li>
      
          <?php foreach ($order_actions as $module) { ?>
          <li>
            <fieldset title="<?php echo htmlspecialchars($module['description']); ?>">
              <legend><?php echo $module['name']; ?></legend>
              <div class="btn-group">
                <?php foreach ($module['actions'] as $action) echo functions::form_draw_button('order_action', array($module['id'].':'.$action['id'], $action['title']), 'submit', 'formtarget="'. htmlspecialchars($action['target']) .'" title="'. htmlspecialchars($action['description']) .'"'); ?>
              </div>
            </fieldset>
          </li>
          <?php } ?>
        </ul>
      </p>

    <?php echo functions::form_draw_form_end(); ?>
  </div>

  <div class="panel-footer">
    <?php echo functions::draw_pagination($num_pages); ?>
  </div>
</div>

<script>
  $('select[name="order_status_id"] option[value=""]').text('-- <?php echo language::translate('title_order_status', ''); ?> --');

  $('input[name="query"]').keypress(function(e) {
    if (e.which == 13) {
      e.preventDefault();
      $(this).closest('form').submit();
    }
  });

  $('form[name="search_form"] select').change(function(){
    $(this).closest('form').submit();
  });

  $('.data-table input[name^="orders["]').change(function() {
    if ($('.data-table input[name^="orders["]:checked').length > 0) {
      $('#order-actions button').removeAttr('disabled');
    } else {
      $('#order-actions button').attr('disabled', 'disabled');
    }
  }).trigger('change');


  $('table').on('click', '.fa-star-o', function(e){
    e.stopPropagation();
    var star = this;
    $.post('', 'star&order_id='+$(star).closest('tr').data('id'), function(data) {
      $(star).replaceWith('<?php echo functions::draw_fonticon('fa-star', 'style="color: #f2b01e;"'); ?>');
    });
    return false;
  });

  $('table').on('click', '.fa-star', function(e){
    var star = this;
    $.post('', 'unstar&order_id='+$(star).closest('tr').data('id'), function(data) {
      $(star).replaceWith('<?php echo functions::draw_fonticon('fa-star-o', 'style="color: #ccc;"'); ?>');
    });
    return false;
  });
</script>