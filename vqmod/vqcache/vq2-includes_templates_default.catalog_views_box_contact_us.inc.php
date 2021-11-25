<section id="box-contact-us" class="box">

  <div class="row">
    <div class="col-md-6">

      <h1><?php echo language::translate('title_contact_us', 'Contact Us'); ?></h1>

      <?php echo functions::form_draw_form_begin('contact_form', 'post'); ?>

        <div class="form-group">
          <label><?php echo language::translate('title_name', 'Name'); ?></label>
          <?php echo functions::form_draw_text_field('name', true, 'required="required"'); ?>
        </div>

        <div class="form-group">
          <label><?php echo language::translate('title_email_address', 'Email Address'); ?></label>
          <?php echo functions::form_draw_email_field('email', true, 'required="required"'); ?>
        </div>

        <div class="form-group">
          <label><?php echo language::translate('title_subject', 'Subject'); ?></label>
          <?php echo functions::form_draw_text_field('subject', true, 'required="required"'); ?>
        </div>

        <div class="form-group">
          <label><?php echo language::translate('title_message', 'Message'); ?></label>
          <?php echo functions::form_draw_textarea('message', true, 'required="required" style="height: 250px;"'); ?>
        </div>

        <?php if (settings::get('captcha_enabled')) { ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label><?php echo language::translate('title_captcha', 'CAPTCHA'); ?></label>
            
            <?php echo functions::recaptcha_draw(); ?>
      
          </div>
        </div>
        <?php } ?>

             
         <p><?php echo functions::form_draw_button('send', language::translate('title_send', 'Send'), 'submit', 'style="width: 150px"'); ?></p>
      

      <?php echo functions::form_draw_form_end(); ?>
    </div>

    <div class="col-md-6">
      <h2><?php echo language::translate('title_contact_details', 'Contact Details'); ?></h2>

      <p class="address"><?php echo nl2br(settings::get('store_postal_address')); ?></p>

      <?php if (settings::get('store_phone')) { ?><p class="phone"><?php echo functions::draw_fonticon('fa-phone'); ?> <a href="tel:<?php echo settings::get('store_phone'); ?>"><?php echo settings::get('store_phone'); ?></a></p><?php } ?>
     
         <?php echo functions::draw_fonticon('fa-phone'); ?> <a href="tel:+6012 392 5533">+6012 392 5533 - Jansen 
         </br>
         </br>
         <span style="color: #333333;"><?php echo functions::draw_fonticon('fa-phone'); ?></span> <a href="tel:+6016 357 6838">+6016 357 6838 - David
         </br>
      

      <p class="email"><?php echo functions::draw_fonticon('fa-envelope'); ?> <a href="mailto:<?php echo settings::get('store_email'); ?>"><?php echo settings::get('store_email'); ?></a></p>
     
         <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/box_store_map.inc.php'); ?>
      
    </div>

  </div>
</section>
     
        <div class="separator" style="clear: both; text-align: center;">
        <img src="<?php echo WS_DIR_IMAGES; ?>Warehouse.jpg"
		style="max-width: 100%; max-height: 100%;" alt="<?php echo WS_DIR_IMAGES; ?>Warehouse.jpg"onContextMenu="return false;">
        </div>
      