<?php
  if (!isset($_GET['page'])) $_GET['page'] = 1;
?>
<div class="pull-right"><?php echo functions::form_draw_link_button(document::link('', array('doc' => 'edit_customer_group'), true), language::translate('title_create_new_customer_group', 'Create New Group'), '', 'add'); ?></div>
<div class="pull-right" style="padding-right: 10px;"><?php echo functions::form_draw_form_begin('search_form', 'get', '', false, 'onsubmit="return false;"') . functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') .'"  onkeydown=" if (event.keyCode == 13) location=(\''. document::link('', array(), true, array('page', 'query')) .'&query=\' + this.value)"') . functions::form_draw_form_end(); ?></div>

<h1><?php echo $app_icon; ?> <?php echo language::translate('title_customer_groups', 'Customer Groups'); ?></h1>

<?php echo functions::form_draw_form_begin('customer_groups_form', 'post'); ?>

  <table class="table table-striped data-table">
    <thead>
      <tr>
        <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle'); ?></th>
        <th><?php echo language::translate('title_id', 'ID'); ?></th>
        <th class="main"><?php echo language::translate('title_name', 'Name'); ?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
<?php
  $customer_groups_query = database::query(
    "select * from ". DB_TABLE_CUSTOMER_GROUPS ."
    order by name;"
  );

  if (database::num_rows($customer_groups_query) > 0) {

    if ($_GET['page'] > 1) database::seek($customer_groups_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($customer_group = database::fetch($customer_groups_query)) {
?>
      <tr>
        <td><?php echo functions::form_draw_checkbox('customer_groups['.$customer_group['id'].']', $customer_group['id']); ?></td>
        <td><?php echo $customer_group['id']; ?></td>
        <td><a href="<?php echo document::href_link('', array('doc' => 'edit_customer_group', 'customer_group_id' => $customer_group['id']), true); ?>"><?php echo $customer_group['name']; ?></a></td>
        <td><a href="<?php echo document::href_link('', array('doc' => 'edit_customer_group', 'customer_group_id' => $customer_group['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
      </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"><?php echo language::translate('title_customer_groups', 'Customer Groups'); ?>: <?php echo database::num_rows($customer_groups_query); ?></td>
      </tr>
    </tfoot>
  </table>

<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::draw_pagination(ceil(database::num_rows($customer_groups_query)/settings::get('data_table_rows_per_page')));
