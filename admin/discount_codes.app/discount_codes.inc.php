<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;

?>
<div class="pull-right"><?php echo functions::form_draw_link_button(document::link('', array('doc' => 'edit_discount_code'), true), language::translate('title_create_new_code', 'Create New Code'), '', 'add'); ?></div>
<h1 ><?php echo $app_icon; ?> <?php echo language::translate('title_discount_codes', 'Discount Codes'); ?></h1>

<?php echo functions::form_draw_form_begin('discount_codes_form', 'post'); ?>

  <table class="table table-striped data-table">
    <thead>
      <tr>
        <th><?php echo functions::form_draw_checkbox('checkbox_toggle', '', ''); ?></th>
        <th><?php echo language::translate('title_id', 'ID'); ?></th>
        <th><?php echo language::translate('title_code', 'Code'); ?></th>
        <th class="main"><?php echo language::translate('title_description', 'Description'); ?></th>
        <th><?php echo language::translate('title_discount', 'Discount'); ?></th>
        <th class="text-center"><?php echo language::translate('title_date_valid_from', 'Valid From'); ?></th>
        <th class="text-center"><?php echo language::translate('title_date_valid_to', 'Valid To'); ?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    </tbody>
<?php
  $discount_codes_query = database::query(
    "select * from ". DB_TABLE_DISCOUNT_CODES ."
    order by date_created desc;"
  );

  if (database::num_rows($discount_codes_query) > 0) {

    if ($_GET['page'] > 1) database::seek($discount_codes_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($discount_code = database::fetch($discount_codes_query)) {
?>
      <tr<?php echo empty($discount_code['status']) ? ' class="semi-transparent"' : ''; ?>>
        <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($discount_code['status']) ? '#99cc66' : '#ff6666') .';"'); ?> <?php echo functions::form_draw_checkbox('discount_codes['.$discount_code['id'].']', $discount_code['id'], (isset($_POST['discount_codes']) && in_array($discount_code['id'], $_POST['discount_codes'])) ? $discount_code['id'] : false); ?></td>
        <td><?php echo $discount_code['id']; ?></td>
        <td><?php echo $discount_code['code']; ?></td>
        <td><?php echo $discount_code['description']; ?></td>
        <td><?php echo (strpos($discount_code['discount'], '%') !== false) ? $discount_code['discount'] : currency::format($discount_code['discount']); ?></td>
        <td class="text-right"><?php echo ($discount_code['date_valid_from'] > 1970) ? strftime(language::$selected['format_datetime'], strtotime($discount_code['date_valid_from'])) : '-'; ?></td>
        <td class="text-right"><?php echo ($discount_code['date_valid_to'] > 1970) ? strftime(language::$selected['format_datetime'], strtotime($discount_code['date_valid_to'])) : '-'; ?></td>
        <td><a href="<?php echo document::href_link('', array('doc' => 'edit_discount_code', 'discount_code_id' => $discount_code['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
      </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="8"><?php echo language::translate('title_discount_codes', 'Discount Codes'); ?>: <?php echo database::num_rows($discount_codes_query); ?></td>
      </tr>
    </tfoot>
  </table>

<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::draw_pagination(ceil(database::num_rows($discount_codes_query)/settings::get('data_table_rows_per_page'))); ?>