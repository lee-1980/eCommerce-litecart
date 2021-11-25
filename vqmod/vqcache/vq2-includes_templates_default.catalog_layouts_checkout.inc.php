<!DOCTYPE html>
<html lang="{snippet:language}" dir="{snippet:text_direction}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{snippet:title}</title>

<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{snippet:template_path}css/framework.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/app.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/checkout.min.css" />
{snippet:head_tags}
{snippet:style}
</head>
<body>
<div id="page" class="twelve-eighty">

      </br>
  <header id="header" class="row nowrap center">
        <div class="col-xs-auto text-center">
    <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
     <img src="<?php echo document::href_link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
    </a>
        </div>
  </header>
<header id="header1" class="row nowrap">
        <div class="col-xs-auto text-right">
            <div id="box_cart">
                <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_search.inc.php'); ?>

            </div>
        </div>
    </header>
    <div class="col-xs-auto text-left">
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_menu.inc.php'); ?>
    </div>
   













<main id="page">
  {snippet:content}
</main>

{snippet:foot_tags}
<script src="{snippet:template_path}js/app.min.js"></script>
{snippet:javascript}
</div>
</body>
</html>