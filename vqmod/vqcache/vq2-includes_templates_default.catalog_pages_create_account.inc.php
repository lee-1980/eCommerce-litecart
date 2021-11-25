<div id="sidebar">
  <div id="column-left">
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_customer_service_links.inc.php'); ?>
  </div>
</div>


      <style>
          #valid-msg {
              color: #00C900;
          }
          #error-msg {
              color: red;
          }
          #error-msg.hidden, #valid-msg.hidden {
              display: none;
          }
      </style>
      
<div id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <section id="box-create-account" class="box">

    <h1><?php echo language::translate('title_create_account', 'Create Account'); ?></h1>

    <?php echo functions::form_draw_form_begin('customer_form', 'post', false, false, 'style="max-width: 640px;"'); ?>

      <?php if (settings::get('customer_field_company') || settings::get('customer_field_tax_id')) { ?>
      <div class="row">
        <?php if (settings::get('customer_field_company')) { ?>
        <div class="form-group col-xs-6">
          <label><?php echo language::translate('title_company', 'Company'); ?> (<?php echo language::translate('text_or_leave_blank', 'Or leave blank'); ?>)</label>
          <?php echo functions::form_draw_text_field('company', true); ?>
        </div>
        <?php } ?>

        <?php if (settings::get('customer_field_tax_id')) { ?>
        <div class="form-group col-xs-6">
          <label><?php echo language::translate('title_tax_id', 'Tax ID'); ?></label>
          <?php echo functions::form_draw_text_field('tax_id', true); ?>
        </div>
        <?php } ?>
      </div>
      <?php } ?>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_firstname', 'First Name'); ?></label>
          <?php echo functions::form_draw_text_field('firstname', true, 'required="required"'); ?>
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_lastname', 'Last Name'); ?></label>
          <?php echo functions::form_draw_text_field('lastname', true, 'required="required"'); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_address1', 'Address 1'); ?></label>
          
         <?php echo functions::form_draw_text_field('address1', true, 'required="required"'); ?>
        
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_address2', 'Address 2'); ?></label>
          <?php echo functions::form_draw_text_field('address2', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_postcode', 'Postal Code'); ?></label>
          
         <?php echo functions::form_draw_text_field('postcode', true, 'required="required"'); ?>
        
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_city', 'City'); ?></label>
          
         <?php echo functions::form_draw_text_field('city', true, 'required="required"'); ?>
        
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_country', 'Country'); ?></label>
          <?php echo functions::form_draw_countries_list('country_code', true, false, 'required="required"'); ?>
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_zone_state_province', 'Zone/State/Province'); ?></label>
          <?php echo functions::form_draw_zones_list(isset($_POST['country_code']) ? $_POST['country_code'] : '', 'zone_code', true, false, 'required="required"'); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_email', 'Email'); ?></label>
          <?php echo functions::form_draw_email_field('email', true, 'required="required"'); ?>
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_phone', 'Phone'); ?></label>
          
         <?php echo functions::form_draw_phone_field('phone', true, 'required="required"'); ?>
         <span id="valid-msg" class="hidden">✓ Valid</span>
         <span id="error-msg" class="hidden"></span>
        
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_desired_password', 'Desired Password'); ?></label>
          <?php echo functions::form_draw_password_field('password', '', 'required="required"'); ?>
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_confirm_password', 'Confirm Password'); ?></label>
          <?php echo functions::form_draw_password_field('confirmed_password', '', 'required="required"'); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="checkbox">
          <?php echo functions::form_draw_checkbox('newsletter', true); ?> <?php echo language::translate('consent_newsletter', 'I would like to be notified occasionally via e-mail when there are new products or campaigns.'); ?>
        </label>
      </div>

      <?php if ($consent) { ?>
      <p class="consent">
        <div class="checkbox">
          <?php echo '<label>'. functions::form_draw_checkbox('terms_agreed', '1', true, 'required="required"') .' '. $consent .'</label>'; ?>
        </div>
      </p>
      <?php } ?>


         <div style="margin:0 0 1em 0; color:#fd0000;"><strong><?php echo language::translate('title_account_activation', 'Account activation'); ?></strong>
         </div>
         </br>
        
      <?php if (settings::get('captcha_enabled')) { ?>
      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_captcha', 'CAPTCHA'); ?></label>
          
          <?php echo functions::recaptcha_draw(); ?>
      
        </div>
      </div>
      <?php } ?>

      
         <div class="btn-group_signin">
        
        <?php echo functions::form_draw_button('create_account', language::translate('title_create_account', 'Create Account')); ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </section>
</div>

<script>

    var input = document.querySelector('input[name="phone"]'),
       errorMsg = document.querySelector("#error-msg"),
       validMsg = document.querySelector("#valid-msg");

    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

    var ini = window.intlTelInput(input, {
        initialCountry: $('select[name="country_code"]').val(),
        preferredCountries: ['my', 'sg', 'jp', 'cn'],
        nationalMode:false,
        allowDropdown:false,
        utilsScript:"<?php echo WS_DIR_APP . 'ext/intel_telephone/js/utils.js'; ?>"
    });

    var reset = function() {
      input.classList.remove("error");
      errorMsg.innerHTML = "";
      errorMsg.classList.add("hidden");
      validMsg.classList.add("hidden");
    };

    $(document.body).on('change', 'select[name="country_code"]', function(){
       var country = $(this).val();
       ini.setCountry(country);
    });

    // on blur: validate
    input.addEventListener('blur', function() {
       reset();
       if (input.value.trim()) {
         if (ini.isValidNumber()) {
             validMsg.classList.remove("hidden");
             $('button[name="create_account"]').prop('disabled', false);
         } else {
             input.classList.add("error");
             var errorCode = ini.getValidationError();
             errorMsg.innerHTML = errorMap[errorCode];
             errorMsg.classList.remove("hidden");
             $('button[name="create_account"]').prop('disabled', true);
         }
       }
    });

    // on keyup / change flag: reset
    input.addEventListener('change', reset);
    input.addEventListener('keyup', reset);
      
  $('#box-create-account').on('change', ':input', function() {
    if ($(this).val() == '') return;
    $('body').css('cursor', 'wait');
    $.ajax({
      url: '<?php echo document::ilink('ajax/get_address.json'); ?>?trigger='+$(this).attr('name'),
      type: 'post',
      data: $(this).closest('form').serialize(),
      cache: false,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        if (console) console.warn(errorThrown.message);
      },
      success: function(data) {
        if (data['alert']) {
          alert(data['alert']);
          return;
        }
        $.each(data, function(key, value) {
          console.log(key +' '+ value);
          if ($('input[name="'+key+'"]').length && $('input[name="'+key+'"]').val() == '') $('input[name="'+key+'"]').val(data[key]);
        });
      },
      complete: function() {
        $('body').css('cursor', 'auto');
      }
    });
  });

  $('select[name="country_code"]').change(function(e) {

    if ($(this).find('option:selected').data('tax-id-format')) {
      $('input[name="tax_id"]').attr('pattern', $(this).find('option:selected').data('tax-id-format'));
    } else {
      $('input[name="tax_id"]').removeAttr('pattern');
    }

    if ($(this).find('option:selected').data('postcode-format')) {
      $('input[name="postcode"]').attr('pattern', $(this).find('option:selected').data('postcode-format'));
    } else {
      $('input[name="postcode"]').removeAttr('pattern');
    }

    if ($(this).find('option:selected').data('phone-code')) {
      $('input[name="phone"]').attr('placeholder', '+' + $(this).find('option:selected').data('phone-code'));
    } else {
      $('input[name="phone"]').removeAttr('placeholder');
    }

    $('body').css('cursor', 'wait');
    $.ajax({
      url: '<?php echo document::ilink('ajax/zones.json'); ?>?country_code=' + $(this).val(),
      type: 'get',
      cache: true,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        if (console) console.warn(errorThrown.message);
      },
      success: function(data) {
        $("select[name='zone_code']").html('');
        if (data.length) {
          $('select[name="zone_code"]').prop('disabled', false);
          $.each(data, function(i, zone) {
            $('select[name="zone_code"]').append('<option value="'+ zone.code +'">'+ zone.name +'</option>');
          });
        } else {
          $('select[name="zone_code"]').prop('disabled', true);
        }
      },
      complete: function() {
        $('body').css('cursor', 'auto');
      }
    });
  });

  if ($('select[name="country_code"] option:selected').data('tax-id-format')) {
    $('input[name="tax_id"]').attr('pattern', $('select[name="country_code"] option:selected').data('tax-id-format'));
  } else {
    $('input[name="tax_id"]').removeAttr('pattern');
  }

  if ($('select[name="country_code"] option:selected').data('postcode-format')) {
    $('input[name="postcode"]').attr('pattern', $('select[name="country_code"] option:selected').data('postcode-format'));
  } else {
    $('input[name="postcode"]').removeAttr('pattern');
  }

  if ($('select[name="country_code"] option:selected').data('phone-code')) {
    $('input[name="phone"]').attr('placeholder', '+' + $('select[name="country_code"] option:selected').data('phone-code'));
  } else {
    $('input[name="phone"]').removeAttr('placeholder');
  }
</script>