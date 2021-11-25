<div id="content">
  {snippet:notices}

  <section id="box-listing_links" class="box">

    <h1 class="title"><?php echo language::translate('title_listing_links', 'Listing Links'); ?></h1>

    <div class="listing products">

      <?php foreach ($listing_links as $listing_link) { ?>
      <article class="listing_link hover-light">
        <a class="link" href="<?php echo htmlspecialchars($listing_link['link']); ?>">
          <div class="image-wrapper">
            <img class="img-responsive" src="<?php echo htmlspecialchars($listing_link['image']['thumbnail']); ?>" srcset="<?php echo htmlspecialchars($listing_link['image']['thumbnail']); ?> 1x, <?php echo htmlspecialchars($listing_link['image']['thumbnail_2x']); ?> 2x" alt="<?php echo htmlspecialchars($listing_link['name']); ?>" />
          </div>
          <h3 class="caption"><?php echo $listing_link['name']; ?></h3>
        </a>
      </article>
      <?php } ?>

    </div>
  </section>
</div>
