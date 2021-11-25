<div id="sidebar">
  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_category_tree.inc.php'); ?>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_filter.inc.php'); ?>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
</div>

<div id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <article id="box-category" class="box">

    <div class="row">
      <?php if ($_GET['page'] == 1 && $image) { ?>
      <div class="col-md-4">
        <div class="thumbnail">
          <img src="<?php echo document::href_link(WS_DIR_APP . $image); ?>" />
        </div>
      </div>
      <?php } ?>

      <div class="<?php echo $image ? 'col-md-8' : 'col-md-12'; ?>">
        <?php if ($products) { ?>
        
         <div class="btn-group pull-right">
        
<?php
  foreach ($sort_alternatives as $key => $value) {
    if ($_GET['sort'] == $key) {
      
         echo '<span class="btn btn-default_pages active">'. $value .'</span>';
        
    } else {
      
         echo '<a class="btn btn-default_pages" href="'. document::href_ilink(null, array('sort' => $key), true) .'">'. $value .'</a>';
        
    }
  }
?>
        </div>
        <?php } ?>

        
    <h1 class="title hidden-xs"><?php echo $h1_title; 
    if (user::$data['status']) {
        echo ' <a style="position: absolute; z-index: 10;" title="Edit Category" target="_blank" href="' .
            document::link(WS_DIR_ADMIN, array('app' => 'catalog', 'doc' => 'edit_category', 'category_id' => $id)) .
        '"><i class="fa fa-cog"></i></a>';
    }
    ?></h1>
            

        <?php if ($_GET['page'] == 1 && trim(strip_tags($description))) { ?>
        <p class="description"><?php echo $description; ?></p>
        <?php } ?>
      </div>
    </div>

    
        
        





    <section class="listing products">
      <?php foreach ($products as $product) echo functions::draw_listing_product($product, $product['listing_type'], array('category_id')); ?>
    </section>

    <?php echo $pagination; ?>
  </article>
</div>