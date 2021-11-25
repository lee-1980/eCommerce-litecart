<?php if (settings::get('store_visiting_address')) { ?>
<div id="box-store-map" class="box">

  <div class="map" style="height: 400px;" class="shadow">
    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo document::href_link('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.038859741143!2d101.66802901475717!3d3.0843038977539914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc3628bf4dcc05%3A0x150d9dadbbcd20d6!2sIT%20Toys!5e0!3m2!1sen!2smy!4v1614179244133!5m2!1sen!2smy', array('q' => settings::get('store_visiting_address'), 'output' => 'svembed')); ?>"></iframe>
  </div>

</div>
<?php } ?>





