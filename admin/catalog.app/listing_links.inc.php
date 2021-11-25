<?php
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

  breadcrumbs::add(language::translate('title_listing_links', 'Listing Links'));

  if (isset($_POST['enable']) || isset($_POST['disable'])) {

    try {
      if (empty($_POST['listing_links'])) throw new Exception(language::translate('error_must_select_listing_links', 'You must select listing links'));

      foreach ($_POST['listing_links'] as $listing_link_id) {
        $listing_link = new ent_listing_link($listing_link_id);
        $listing_link->data['status'] = !empty($_POST['enable']) ? 1 : 0;
        $listing_link->save();
      }

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link());
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

// Table Rows
  $listing_links = array();

  $listing_links_query = database::query(
    "select * from ". DB_TABLE_LISTING_LINKS ."
    order by name asc;"
  );

  if ($_GET['page'] > 1) database::seek($listing_links_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

  $page_items = 0;
  while ($listing_link = database::fetch($listing_links_query)) {

    $products_query = database::query(
      "select id from ". DB_TABLE_PRODUCTS ."
      where listing_link_id = ". (int)$listing_link['id'] .";"
    );

    $listing_link['num_products'] = database::num_rows($products_query);

    $listing_links[] = $listing_link;
    if (++$page_items == settings::get('data_table_rows_per_page')) break;
  }

// Number of Rows
  $num_rows = database::num_rows($listing_links_query);

// Pagination
  $num_pages = ceil($num_rows/settings::get('data_table_rows_per_page'));
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_listing_links', 'Listing Links'); ?>
  </div>

  <div class="panel-action">
    <ul class="list-inline">
      <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc' => 'edit_listing_link')), language::translate('title_add_new_listing_link', 'Add New Listing Link'), '', 'add'); ?></li>
    </ul>
  </div>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('listing_links_form', 'post'); ?>

      <table class="table table-striped table-hover data-table">
        <thead>
          <tr>
            <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle', 'data-toggle="checkbox-toggle"'); ?></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th class="main"><?php echo language::translate('title_name', 'Name'); ?></th>
            <th><?php echo language::translate('title_products', 'Products'); ?></th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($listing_links as $listing_link) { ?>
          <tr class="<?php echo empty($listing_link['status']) ? 'semi-transparent' : null; ?>">
            <td><?php echo functions::form_draw_checkbox('listing_links['. $listing_link['id'] .']', $listing_link['id']); ?></td>
            <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($listing_link['status']) ? '#88cc44' : '#ff6644') .';"'); ?></td>
            <td><?php echo $listing_link['featured'] ? functions::draw_fonticon('fa-star', 'style="color: #ffd700;"') : ''; ?></td>
            <td><img src="<?php echo document::href_link($listing_link['image'] ? WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $listing_link['image'], 16, 16, 'FIT_USE_WHITESPACING') : 'images/no_image.png'); ?>" alt="" style="width: 16px; height: 16px; vertical-align: bottom;" /> <a href="<?php echo document::href_link('', array('doc' => 'edit_listing_link', 'listing_link_id' => $listing_link['id']), array('app')); ?>"><?php echo $listing_link['name']; ?></a></td>
            <td class="text-center"><?php echo (int)$listing_link['num_products']; ?></td>
            <td class="text-right"><a href="<?php echo document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_listing_link', 'listing_link_id' => $listing_link['id'])); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6"><?php echo language::translate('title_listing_links', 'Listing Links'); ?>: <?php echo $num_rows; ?></td>
          </tr>
        </tfoot>
      </table>

      <div class="btn-group">
        <?php echo functions::form_draw_button('enable', language::translate('title_enable', 'Enable'), 'submit', '', 'on'); ?>
        <?php echo functions::form_draw_button('disable', language::translate('title_disable', 'Disable'), 'submit', '', 'off'); ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>

  <div class="panel-footer">
    <?php echo functions::draw_pagination($num_pages); ?>
  </div>
</div>
