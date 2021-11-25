<?php

  if (empty($_GET['discount_code_id'])) {
    $discount_code = new ent_discount_code();
  } else {
    $discount_code = new ent_discount_code($_GET['discount_code_id']);
  }

  if (empty($_POST)) {
    foreach ($discount_code->data as $key => $value) {
      $_POST[$key] = $value;
    }
  }

  if (!empty($_POST['save'])) {

    if (empty(notices::$data['errors'])) {

      if (empty($_POST['status'])) $_POST['status'] = 0;
      if (empty($_POST['customers'])) $_POST['customers'] = 0;
      if (empty($_POST['categories'])) $_POST['categories'] = 0;
      if (empty($_POST['manufacturers'])) $_POST['manufacturers'] = 0;
      if (empty($_POST['products'])) $_POST['products'] = 0;
      if (empty($_POST['limited'])) $_POST['limited'] = 0;

      $fields = array(
        'status',
        'code',
        'description',
        'discount',
        'min_subtotal_amount',
        'max_use_customer',
        'max_use_total',
        'customers',
        'categories',
        'manufacturers',
        'products',
        'limited',
        'date_valid_from',
        'date_valid_to',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $discount_code->data[$field] = $_POST[$field];
      }

      $discount_code->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link('', array('app' => $_GET['app'], 'doc' => 'discount_codes')));
      exit;
    }
  }

  if (!empty($_POST['delete']) && $discount_code) {
    $discount_code->delete();
    notices::add('success', language::translate('success_post_deleted', 'Post deleted'));
    header('Location: '. document::link('', array('app' => $_GET['app'], 'doc' => 'discount_codes')));
    exit();
  }

?>
<h1><?php echo $app_icon; ?> <?php echo !empty($discount_code->data['id']) ? language::translate('title_edit_discount_code', 'Edit Discount Code') : language::translate('title_create_new_discount_code', 'Create New Discount Code'); ?></h1>

<?php echo functions::form_draw_form_begin('form_discount_code', 'post', null, false, 'style="max-width: 640px;"'); ?>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_status', 'Status'); ?></label>
      <?php echo functions::form_draw_toggle('status', true, 'e/d'); ?>
    </div>

    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_code', 'Code'); ?></label>
      <?php echo functions::form_draw_text_field('code', true); ?>
    </div>
  </div>

  <div class="form-group">
    <label><?php echo language::translate('title_description', 'Description'); ?></label>
    <?php echo functions::form_draw_text_field('description', true); ?>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_discount', 'Discount'); ?></label>
      <?php echo functions::form_draw_text_field('discount', true, 'placeholder="E.g. 50.00 or 10%"'); ?>
    </div>

    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_min_subtotal_amount', 'Min. Subtotal Amount'); ?></label>
      <?php echo functions::form_draw_currency_field(settings::get('store_currency_code'), 'min_subtotal_amount', true); ?>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_max_use_customer', 'Max. Uses Customer'); ?></label>
      <?php echo functions::form_draw_number_field('max_use_customer', true, '0'); ?> 0 = <?php echo language::translate('title_unlimited', 'Unlimited'); ?>
    </div>

    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_max_use_total', 'Max. Uses Total'); ?></label>
      <?php echo functions::form_draw_number_field('max_use_total', true, '0'); ?> 0 = <?php echo language::translate('title_unlimited', 'Unlimited'); ?>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_date_valid_from', 'Date Valid From'); ?></label>
      <?php echo functions::form_draw_datetime_field('date_valid_from', true); ?>
    </div>

    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_date_valid_to', 'Date Valid To'); ?></label>
      <?php echo functions::form_draw_datetime_field('date_valid_to', true); ?>
    </div>
  </div>

  <h2><?php echo language::translate('title_apply_to', 'Apply To'); ?></h2>

  <div class="form-group">
    <label><?php echo functions::form_draw_checkbox('customers_toggle', '1', !empty($_POST['customers']) ? '1' : 0); ?> <?php echo language::translate('title_customers', 'Customers'); ?></label>
    <?php echo functions::form_draw_customers_list('customers[]', true, true, 'style="max-height: 175px;"'); ?>
  </div>

  <div class="form-group">
    <label><?php echo functions::form_draw_radio_button('type', 'all', (empty($_POST['categories']) && empty($_POST['manufacturers']) && empty($_POST['products'])) ? 'all' : 0); ?> <?php echo language::translate('text_any_product', 'Any product'); ?></label>
  </div>

  <div class="form-group">
    <label><?php echo functions::form_draw_radio_button('type', 'categories', !empty($_POST['categories']) ? 'categories' : 0); ?> <?php echo language::translate('title_categories', 'Categories'); ?></label>
    <?php echo functions::form_draw_categories_list('categories[]', true, true, 'style="max-height: 175px;"'); ?>
  </div>

  <div class="form-group">
    <label><?php echo functions::form_draw_radio_button('type', 'manufacturers', !empty($_POST['manufacturers']) ? 'manufacturers' : 0); ?> <?php echo language::translate('title_manufacturers', 'Manufacturers'); ?></label>
    <?php echo functions::form_draw_manufacturers_list('manufacturers[]', true, true, 'style="max-height: 175px;"'); ?>
  </div>

  <div class="form-group">
    <label><?php echo functions::form_draw_radio_button('type', 'products', !empty($_POST['products']) ? 'products' : 0); ?> <?php echo language::translate('title_products', 'Products'); ?></label>
    <?php echo functions::form_draw_products_list('products[]', true, true, 'style="max-height: 175px;"'); ?>
  </div>

  <div class="form-group">
    <label><?php echo language::translate('title_limit', 'Limitations'); ?></label>
    <div class="checkbox">
      <label><?php echo functions::form_draw_checkbox('limited', '1', true); ?> <?php echo language::translate('text_one_match_is_one_usage', 'One matched product is one usage of the code'); ?></label>
    </div>
  </div>

  <div class="btn-group">
    <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
    <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
    <?php echo (isset($discount_code->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
  </div>

<?php echo functions::form_draw_form_end(); ?>

<script>
  $('input[name="customers_toggle"]').change(function() {
    if (!$('input[name="customers_toggle"]').is(':checked')) {
      $('select[name="customers[]"]').attr('disabled', 'disabled').slideUp('fast');
    } else {
      $('select[name="customers[]"]').removeAttr('disabled').slideDown('fast');
    }
  });

  $('input[name="customers_toggle"]').trigger('change');

  $('input[name="type"]').change(function() {
    $('input[name="type"]').each(function() {
      if (!$(this).is(':checked')) {
        $('select[name="'+ $(this).val() +'[]"]').attr('disabled', 'disabled').slideUp('fast');
      } else {
        $('select[name="'+ $(this).val() +'[]"]').removeAttr('disabled').slideDown('fast');
      }
    });
  });

  $('input[name="type"]').trigger('change');
</script>