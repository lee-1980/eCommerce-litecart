<!DOCTYPE html>
<html lang="{snippet:language}" dir="{snippet:text_direction}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">



<title>{snippet:title}</title>

<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{snippet:template_path}css/framework.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/app.min.css" />
{snippet:head_tags}
{snippet:style}
<?php include  FS_DIR_HTTP_ROOT.WS_DIR_EXT . 'pwa/boxes/box_pwa_header.inc.php';?>
</head>
<body>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 
  ga('create', 'UA-126598525-1', 'https://ittoysline.com/en/');
  ga('send', 'pageview');
</script>    


        
<div class="hamburger-menu">
		<div class="burger-menu">
			<span></span>
		</div>
   </div>
   <div class="fixed-sidebar">
		<div class="sidebar-menu">
			<div id="sidebar">
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_category_tree.inc.php'); ?>
    
        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_filter.inc.php'); ?>

        <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
    
</div>
    </div>
	</div>
   <div class="bg-blur"></div>
      

      </br>
     <!--   <div id="page" class="twelve-eighty"> -->
    <div id="page"> 
  <?php include vmod::check(FS_DIR_TEMPLATE . 'views/box_cookie_notice.inc.php'); ?>
      
  <header id="header" class="row nowrap center"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <div class="col-xs-auto text-center">
    <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
     <img src="<?php echo document::href_link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
    </a>
        </div>
  </header>
<!--    <header id="header1" class="row nowrap center"> -->
    <!--   <div id="page" class="twelve-eighty"> -->
     <div id="page">  
        <div class="col-xs-auto text-right">
            <div id="box_cart">
                <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
            </div>
        </div>
    </header>


    
<script src="{snippet:template_path}js/app.min.js"></script>
<script>
    $(document).ready(function(){
        	// Burger Menu JS
	$(".burger-menu").click(function() {
    // Sidebar JS
    $(".fixed-sidebar").toggleClass("active");
    // Mobile Burger Menu JS
    $(".hamburger-menu").toggleClass("active");
    // Body JS
    $("body").toggleClass("overflow-hidden");
    // Blur JS
    $(".bg-blur").toggleClass("active");
  });
  // Blur JS
  $(".bg-blur").click(function() {
  	// Blur JS
    $(this).removeClass("active");
  	// Sidebar JS
    $(".fixed-sidebar").removeClass("active");
    // Mobile Burger Menu JS
    $(".hamburger-menu").removeClass("active");
    // Body JS
    $("body").removeClass("overflow-hidden");
  })
    });
</script> 

<!-- GetButton.io widget -->
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "+60123925533", // WhatsApp number
            telegram: "t.me/IT_Toys_Bot.", // Telegram bot username
            call_to_action: "Message us", // Call to action
            button_color: "#A8CE50", // Color of button
            position: "right", // Position may be 'right' or 'left'
            order: "telegram,whatsapp", // Order of buttons
        };
        var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
<!-- /GetButton.io widget -->
      














    </div>
  </header>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_menu.inc.php'); ?>


  <main id="main">
    {snippet:content}
  </main>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_footer.inc.php'); ?>
</div>

<a id="scroll-up" class="hidden-print" href="#">
  <?php echo functions::draw_fonticon('fa-chevron-circle-up fa-3x', 'style="color: #000;"'); ?>
</a>

{snippet:foot_tags}
<script src="{snippet:template_path}js/app.min.js"></script>
{snippet:javascript}

        <script type="text/javascript" src="https://unpkg.com/@zxing/library@0.18.2/umd/index.js"></script>
      

        <script type="text/javascript">
        $('body').on('click', '.product-column .wish', function(){
          $(this).addClass('loading');
          var productID = $(this).closest('article').find('a.link').data('id');
          updateWishList(productID, $(this));
        });
        $('body').on('click', '.box-wishlist .wish', function(){
          $(this).addClass('loading');
          var productID = $(this).closest('article').data('id');
          updateWishList(productID, $(this));
        });
        function updateWishList(productId, elem_pro){
        var url = '<?php echo document::ilink('ajax/wishlist'); ?>';
           $.ajax({
      type: 'post',
      url: url,
      data: {id: productId},
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
      },
      success: function(data) {
        $(elem_pro).removeClass('loading');
        console.log(data);
        if(data.status==="200removed!"){
        $(elem_pro).find('.fa-heart').removeClass('wished');
         if (window.location.href.indexOf("wishlist") > -1) {
              $(elem_pro).closest('article.product-column').remove();
         }
        }
        else{
        $(elem_pro).find('.fa-heart').addClass('wished');
        }
      }
    });
        }
        </script>

      
</body>
</html>