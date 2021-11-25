<?php

if (!empty($_GET['guest_prices_id'])) {
    $guest_price = new ent_guest_price($_GET['guest_prices_id']);

} else {
    $guest_price = new ent_guest_price();
}

if (empty($_POST)) {
    foreach ($guest_price->data as $key => $value) {
        $_POST[$key] = $value;
    }
}

breadcrumbs::add(!empty($guest_price->data['id']) ? language::translate('title_edit_guest_price', 'Edit Guest Price') : language::translate('title_add_new_guest_price', 'Add New Guest Price'));

if (isset($_POST['save'])) {

    if (empty(notices::$data['errors'])) {

        $fields = array(
            'name',
            'description',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) $guest_price->data[$field] = $_POST[$field];
        }

        $guest_price->save();

        notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
        header('Location: '. document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc' => 'guest_prices')));
        exit;
    }
}

if (isset($_POST['delete'])) {

    $guest_price->delete();

    notices::add('success', language::translate('success_post_deleted', 'Post deleted'));
    header('Location: '. document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc' => 'guest_prices')));
    exit;
}

?>
    <h1><?php echo $app_icon; ?> <?php echo !empty($guest_price->data['id']) ? language::translate('title_edit_guest_price', 'Edit Guest Price') : language::translate('title_add_new_guest_price', 'Add New Guest Price'); ?></h1>

<?php echo functions::form_draw_form_begin('guest_prices_form', 'post', null, false, 'style="max-width: 640px;"'); ?>

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
        <?php echo (isset($guest_price->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
    </div>

<?php echo functions::form_draw_form_end();
