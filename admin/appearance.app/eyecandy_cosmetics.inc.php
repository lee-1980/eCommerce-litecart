<?php
    database::query(
      "CREATE TABLE IF NOT EXISTS ". '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'eyecandy_modules_settings`' ."
      (
        `id` INT AUTO_INCREMENT NOT NULL,
        `module_key` varchar(30) NOT NULL,
        `settings_json` varchar(2000),
        PRIMARY KEY (`id`),
        INDEX name (`module_key`)
      ) 
      CHARACTER SET utf8 COLLATE utf8_general_ci;");
?>

<h1><?php echo('<img width="70px" height="30px" src="'. WS_DIR_IMAGES . 'eye_candy_logo.png'.'" alt="" />'); ?> <?php echo(language::translate('title_eyecandy_cosmetic', 'EyeCandy Cosmetics')); ?></h1>

<?php
  $modules = array(
    /*<input:settings_button_top>*/
    /*<input:settings_button_bottom>*/
  );
?>

<?php echo(functions::form_draw_form_begin('eyecandy_cosmetic_form', 'post', false, true, 'style="max-width: 320px;"')); ?>

  <div class="form-group">
    <label><?php echo language::translate('eyecandy_cosmetic_ext_modules_title', 'Modules:'); ?></label><br/>
      <?php
        if(count($modules))
        {
          foreach ($modules as $module => $info) 
          {
            echo('<a class="btn btn-default" href="'.
              document::href_link(WS_DIR_ADMIN, array('doc' => $info['doc']), array('app')).
              '" alt="'.
              language::translate('title_settings', 'Settings').'">'.$info['title'].' '.
              functions::draw_fonticon('fa-wrench fa-lg').'</a>');
          }
        }
        else
        {
          echo language::translate('eyecandy_cosmetic_ext_no_modules', 'There are no supported modules installed!');
        }
      ?>
  </div>

<?php echo functions::form_draw_form_end(); ?>