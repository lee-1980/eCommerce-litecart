<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>
<h1><?php echo $app_icon; ?> <?php echo language::translate('title_used_discount_codes', 'Used Discount Codes'); ?></h1>

<?php echo functions::form_draw_form_begin('used_codes_form', 'post'); ?>

  <table class="table table-striped data-table">
    <thead>
      <tr>
        <th><?php echo language::translate('title_id', 'ID'); ?></th>
        <th class="main"><?php echo language::translate('title_customer', 'Customer'); ?></th>
        <th class="text-center"><?php echo language::translate('title_code', 'Code'); ?></th>
        <th class="text-center"><?php echo language::translate('title_discount', 'Discount'); ?></th>
        <th class="text-center"><?php echo language::translate('title_tax', 'Tax'); ?></th>
        <th class="text-center"><?php echo language::translate('title_date', 'Date'); ?></th>
      </tr>
    </thead>
    <tbody>
<?php
  $used_codes_query = database::query(
    "select dcu.*, concat(o.customer_firstname, ' ', o.customer_lastname) as customer_name from ". DB_TABLE_DISCOUNT_CODES_USED ." dcu
    left join ". DB_TABLE_ORDERS ." o on (dcu.order_id = o.id)
    order by date_created desc;"
  );

  if (database::num_rows($used_codes_query) > 0) {

    if ($_GET['page'] > 1) database::seek($used_codes_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($used_code = database::fetch($used_codes_query)) {
?>
    <tr>
      <td><?php echo $used_code['id']; ?></td>
      <td><?php echo $used_code['customer_name']; ?></td>
      <td class="text-center"><?php echo $used_code['code']; ?></td>
      <td class="text-right"><?php echo currency::format($used_code['discount']); ?></td>
      <td class="text-right"><?php echo currency::format($used_code['tax']); ?></td>
      <td class="text-right"><?php echo strftime(language::$selected['format_datetime'], strtotime($used_code['date_created'])); ?></td>
    </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="8"><?php echo language::translate('title_used_discount_codes', 'Used Discount Codes'); ?>: <?php echo database::num_rows($used_codes_query); ?></td>
      </tr>
    </tfoot>
  </table>

<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::draw_pagination(ceil(database::num_rows($used_codes_query)/settings::get('data_table_rows_per_page'))); ?>