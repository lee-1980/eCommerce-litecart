<nav id="site-menu" class="navbar hidden-print">

  <div class="navbar-header">
    
      <div class="col-xs-auto text-center">
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_google_translate.inc.php'); ?>
      </div>      
      
       <div class="col-xs-auto text-center">
           <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_region.inc.php'); ?>
       </div>
      



    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>

  <div id="default-menu" class="navbar-collapse collapse">

    <ul class="nav navbar-nav">
      <li class="hidden-xs">
        <a href="<?php echo document::ilink(''); ?>" title="<?php echo language::translate('title_home', 'Home'); ?>"><?php echo functions::draw_fonticon('fa-home'); ?></a>
      </li>



 
      <?php if (!empty(user::$data['status'])) { ?>
      <li class="dropdown mega-dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Admin<span class="caret"></span></a>
      <div class="dropdown-menu"> 
      <iframe width="1000" height="550" frameborder="0" scrolling="no" src="https://onedrive.live.com/embed?resid=3C99177F71C250B3%2128607&authkey=%21AG4BqZdXtrXoufM&em=2&AllowTyping=True&wdHideGridlines=True&wdHideHeaders=True&wdInConfigurator=True"></iframe>
      <?php } ?>
      

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('campaigns'); ?>"><?php echo language::translate('title_campaigns', 'Campaigns'); ?></a>
        </li>
      

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('new_products'); ?>"><?php echo language::translate('title_new_products', 'New Products'); ?></a>
        </li>

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('newarrival_products'); ?>"><?php echo language::translate('title_newarrival_products', 'New Arrival'); ?></a>
        </li>

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('preorderable_products'); ?>"><?php echo language::translate('title_pre_order_products', 'Pre-Order'); ?></a>
        </li>        

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('restock_products'); ?>"><?php echo language::translate('title_restock_products', 'Restock Products'); ?></a>
        </li>          
      

        <li class="new-products dropdown">
          <a href="<?php echo document::href_ilink('wishlist'); ?>"><?php echo language::translate('title_wishlist', 'Wishlist'); ?> <i class="fa fa-heart" aria-hidden="true"></i></a>
        </li>
        <li class="new-products dropdown">
          <a href="https://t.me/ittoys"><?php echo language::translate('title_telegram_channel', 'Channel'); ?> <span style="color: #001ede;"><i class="fa fa-telegram" aria-hidden="true"></i></span></a>
        </li>        

      
      <?php if ($categories) { ?>
      <li class="categories dropdown">
         
      
      
        <ul class="dropdown-menu">
          <?php foreach ($categories as $item) { ?>
          <li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>

      <?php if ($manufacturers) { ?>
      <li class="manufacturers dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo language::translate('title_manufacturers', 'Manufacturers'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <?php foreach ($manufacturers as $item) { ?>
          <li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>

      <?php if ($pages) { ?>
      <li class="information dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo language::translate('title_information', 'Information'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <?php foreach ($pages as $item) { ?>
          <li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>

    <ul class="nav navbar-nav navbar-right">
      <li class="customer-service">
        
       
      
      </li>

      <li class="account dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo functions::draw_fonticon('fa-user'); ?> <?php echo !empty(customer::$data['id']) ? customer::$data['firstname'] : language::translate('title_sign_in', 'Sign In'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <?php if (!empty(customer::$data['id'])) { ?>
            <li><a href="<?php echo document::href_ilink('order_history'); ?>"><?php echo language::translate('title_order_history', 'Order History'); ?></a></li>
            <li><a href="<?php echo document::href_ilink('edit_account'); ?>"><?php echo language::translate('title_edit_account', 'Edit Account'); ?></a></li>
            <li><a href="<?php echo document::href_ilink('logout'); ?>"><?php echo language::translate('title_logout', 'Logout'); ?></a></li>
          <?php } else { ?>
            <li>
              <?php echo functions::form_draw_form_begin('login_form', 'post', document::ilink('login'), false, 'class="navbar-form"'); ?>
                <?php echo functions::form_draw_hidden_field('redirect_url', !empty($_GET['redirect_url']) ? $_GET['redirect_url'] : document::link()); ?>

                <div class="form-group">
                  
      <?php echo functions::form_draw_email_phone_field('email', true, 'required="required" placeholder="'. language::translate('title_phone_number_or_email_address', 'Phone Number or Email Address') .'"'); ?>
      
                </div>

                <div class="form-group">
                  <?php echo functions::form_draw_password_field('password', '', 'placeholder="'. language::translate('title_password', 'Password') .'"'); ?>
                </div>

                <div class="form-group">
                  <div class="checkbox">
                    <label><?php echo functions::form_draw_checkbox('remember_me', '1'); ?> <?php echo language::translate('title_remember_me', 'Remember Me'); ?></label>
                  </div>
                </div>

                
       <div class="btn-group_signin">
      
                  <?php echo functions::form_draw_button('login', language::translate('title_sign_in', 'Sign In')); ?>
                </div>
              <?php echo functions::form_draw_form_end(); ?>
            </li>
            <li class="text-center">
               
      </br>
      <div class="strokeme"> 
      <strong><a href="<?php echo document::href_ilink('create_account'); ?>"><span style="color: black;"><?php echo language::translate('text_new_customers_click_here', 'New customers click here'); ?></strong></span></a></div>
      </br>
      
            </li>

            <li class="text-center">
               
      <div class="strokeme"> 
      <strong><a href="<?php echo document::href_ilink('reset_password'); ?>"><span style="color: black;"><?php echo language::translate('text_lost_your_password', 'Lost your password?'); ?></a></strong></span></a>  
      </br>
       </div>
      </br>
      
            </li>
          <?php } ?>
        </ul>
      </li>
    </ul>
  </div>
</nav>
