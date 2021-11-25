<?php

  if (!empty($_GET['customer_group_id'])) {
    $customer_group = new ent_customer_group($_GET['customer_group_id']);
  } else {
    $customer_group = new ent_customer_group();
  }

  if (empty($_POST)) {
      foreach ($customer_group->data as $key => $value) {
        $_POST[$key] = $value;
      }
    }

  breadcrumbs::add(!empty($customer_group->data['id']) ? language::translate('title_edit_customer_group', 'Edit Customer Group') : language::translate('title_add_new_customer_group', 'Add New Customer Group'));

  if (isset($_POST['save'])) {

    if (empty(notices::$data['errors'])) {

      $fields = array(
        'name',
        'description',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $customer_group->data[$field] = $_POST[$field];
      }

      $customer_group->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link('', array('app' => $_GET['app'], 'doc' => 'customer_groups')));
      exit;
    }
  }

  if (isset($_POST['delete'])) {

    $customer_group->delete();

    notices::add('success', language::translate('success_post_deleted', 'Post deleted'));
    header('Location: '. document::link('', array('app' => $_GET['app'], 'doc' => 'customer_groups')));
    exit;
  }

?>
<h1><?php echo $app_icon; ?> <?php echo !empty($customer_group->data['id']) ? language::translate('title_edit_customer_group', 'Edit Customer Group') : language::translate('title_add_new_customer_group', 'Add New Customer Group'); ?></h1>

<?php echo functions::form_draw_form_begin('customer_group_form', 'post', null, false, 'style="max-width: 640px;"'); ?>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_name', 'Name'); ?></label>
      <?php echo functions::form_draw_text_field('name', true); ?>
    </div>
  </div>

  <div class="form-group">
    <label><?php echo language::translate('title_description', 'Description'); ?></label>
    <?php echo functions::form_draw_textarea('description', true); ?>
  </div>

  <div class="btn-group">
    <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
    <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
    <?php echo (isset($customer_group->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
  </div>

<?php echo functions::form_draw_form_end();
