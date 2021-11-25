

				<script>
				$('cart').append('<style>.sticky {position:fixed;z-index:2;right:0;top:0px;}</style>');
				var div = '#sticky';
				var windowScrollTop = $(window).scrollTop();
				var divOffsetTop = $(div).offset().top;
				if(windowScrollTop > divOffsetTop){
					$(div).addClass('sticky');
				} else {
					$(div).removeClass('sticky');
				}

				$(window).on('scroll', function(){
					var windowScrollTop = $(window).scrollTop();
					if(windowScrollTop > divOffsetTop){
						$(div).addClass('sticky');
					} else {
						$(div).removeClass('sticky');
					}
				});
			
				</script>
			
</footer>
<div id="sticky">
<div class="col-xs-auto col-left">
<table border="0" cellpadding="0" cellspacing="0" text-align: right; width: 100px;"><tbody>
<tr height="20" style="height: 15.0pt;">
  <td height="20" style="height: 15.0pt; width: 100%;" width="100%">
      <div class="col-xs-auto col-right">
     <div id="search">
       <?php echo functions::form_draw_form_begin('search_form', 'get', document::ilink('search'), false, 'class="navbar-form"'); ?>
       <?php echo functions::form_draw_search_field('query', true, 'placeholder="' . language::translate('text_search_products', 'Search products') . ' &hellip;"', true); ?>
       <?php echo functions::form_draw_form_end(); ?>
     </div>
      </div>
      </td>
  <td style="width: 0pt;" width="0">      
<div id="cart">
  <a href="<?php echo htmlspecialchars($link); ?>">
    <img class="image" src="{snippet:template_path}images/<?php echo !empty($num_items) ? 'cart_filled.svg' : 'cart.png'; ?>" alt="" />
    <div class="badge quantity"><?php echo $num_items ? $num_items : ''; ?></div>
  </a>
</div>
</div>
</td></tr>
</tbody>
</table>
</div>
</div>
      




