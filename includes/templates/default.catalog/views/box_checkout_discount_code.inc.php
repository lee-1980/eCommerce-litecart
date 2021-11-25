<div style="margin-bottom: 10px; max-width: 200px;">
  <?php echo functions::form_draw_form_begin('discount_code_form', 'post', '', false, 'style="max-width: 200px;"'); ?>

    <div class="form-group">
      <label><?php echo language::translate('title_discount_code', 'Discount Code'); ?></label>
      <div class="input-group">
        <?php echo functions::form_draw_text_field('discount_code', true, 'data-size="small"' . (!empty($css_background) ? $css_background : '')); ?>
        <span class="input-group-btn">
          <?php echo functions::form_draw_button('set_discount_code', language::translate('title_set', 'Set'), 'submit', 'class="input-group-btn btn btn-default" disabled="disabled"'); ?>
        </span>
      </div>
    </div>

  <?php echo functions::form_draw_form_end(); ?>
</div>

<script>
  $('input[name="discount_code"]').focus('focus', function(e){
    $('button[name="set_discount_code"]').removeAttr('disabled');
    $("input[name='discount_code']").css('background', '');
  });
  $('input[name="discount_code"]').blur(function(){
    $('button[name="set_discount_code"]').trigger('click');
  });
</script>
