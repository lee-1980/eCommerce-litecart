<div id="sidebar">
  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_listing_link_links.inc.php'); ?>
  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
</div>

<div id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <article id="box-listing_link" class="box">
    <?php if ($products) { ?>
    <div class="btn-group pull-right hidden-xs">
<?php
  foreach ($sort_alternatives as $key => $value) {
    if ($_GET['sort'] == $key) {
      echo '<span class="btn btn-default active">'. $value .'</span>';
    } else {
      echo '<a class="btn btn-default" href="'. document::href_ilink(null, array('sort' => $key), true) .'">'. $value .'</a>';
    }
  }
?>
    </div>
    <?php } ?>

    <h1 class="title"><?php echo $title; ?></h1>

    <?php if ($_GET['page'] == 1 && $description) { ?>
    <p class="description"><?php echo $description; ?></p>
    <?php } ?>

    <?php if ($products) { ?>
    <section class="listing products">
      <?php foreach ($products as $product) echo functions::draw_listing_product($product, 'column', array('listing_link_id')); ?>
    </section>
    <?php } ?>

    <?php echo $pagination; ?>
  </article>
</div>