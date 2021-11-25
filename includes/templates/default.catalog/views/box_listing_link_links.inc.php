<section id="box-listing_link-links" class="box">

  <h2 class="title"><?php echo language::translate('title_listing_links', 'Listing Links'); ?></h2>

  <ul class="nav nav-stacked nav-pills">
    <?php foreach ($listing_links as $listing_link) { ?>
    <li<?php echo (!empty($listing_link['active']) ? ' class="active"' : ''); ?>><a href="<?php echo htmlspecialchars($listing_link['link']); ?>"><?php echo $listing_link['name']; ?></a></li>
    <?php } ?>
  </ul>

</section>
