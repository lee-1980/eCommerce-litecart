<div id="sidebar">
  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_category_tree.inc.php'); ?>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
</div>

<main id="content">
  {snippet:notices}
  {snippet:breadcrumbs}

  <section id="box-new-products" class="box">

    <h1 class="title"><?php echo language::translate('title_wholesale_specialprice_products', 'Restock'); ?></h1>

    <?php if ($products) { ?>
    <div class="listing products" style="margin-bottom: 15px;">
      <?php foreach ($products as $product) echo functions::draw_listing_product($product, 'column'); ?>
    </div>
    <?php } ?>

    <?php echo $pagination; ?>
  </section>
</main>