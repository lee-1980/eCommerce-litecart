</footer>
<dv id="search">
<div class="col-xs-auto text-left">
       <?php echo functions::form_draw_form_begin('search_form', 'get', document::ilink('search'), false, 'class="navbar-form"'); ?>
        <?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;"'); ?>
         <?php echo functions::form_draw_form_end(); ?>
</div>

