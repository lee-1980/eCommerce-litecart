
<main id="content">
    {snippet:notices}
    {snippet:breadcrumbs}
<article id="box-new-products" class="box">

    <h1 class="title"><?php echo language::translate('title_wishlist', 'WishList'); ?></h1>

    <?php if ($products &&count($products)>0) { ?>
        <section class="listing products" style="margin-bottom: 15px;">
            <?php foreach ($products as $product) echo functions::draw_listing_product($product, 'column'); ?>
        </section>
    <?php } else{ ?>
        <h2>No wishlist items!</h2>
    <?php } echo $pagination; ?>
</article>
</main>
