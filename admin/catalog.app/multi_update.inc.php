<?php
  if (!isset($_GET['page'])) $_GET['page'] = '1';
  if (!isset($_GET['query'])) $_GET['query'] = '';
  if (!isset($_GET['category_id'])) $_GET['category_id'] = '';
  if (!isset($_GET['manufacturer_id'])) $_GET['manufacturer_id'] = '';
  if (!isset($_GET['supplier_id'])) $_GET['supplier_id'] = '';
  if (empty($_GET['language_code'])) $_GET['language_code'] = language::$selected['code'];
  if (empty($_GET['currency_code'])) $_GET['currency_code'] = currency::$selected['code'];
  if (empty($_GET['columns'])) $_GET['columns'] = array('status', 'id', 'image', 'code', 'name', 'manufacturer', 'price', 'quantity');

  if (isset($_POST['save'])) {

    foreach (array_keys($_POST['products']) as $product_id) {
      $product = new ent_product($product_id);

      $fields = array(
        'code',
        'sku',
        'gtin',
        'manufacturer_id',
        'supplier_id',
        'name',
        'purchase_price',
        'purchase_price_currency_code',
        'prices',
        'quantity',
        'dim_x',
        'dim_y',
        'dim_z',
        'dim_class',
        'weight',
        'weight_class',
      );

      foreach ($fields as $field) {
        if (isset($_POST['products'][$product_id][$field])) {
          if (is_array($product->data[$field])) {
            $product->data[$field] = array_replace_recursive($product->data[$field], $_POST['products'][$product_id][$field]);
          } else {
            $product->data[$field] = $_POST['products'][$product_id][$field];
          }
        }
      }

      foreach (array_keys($_POST['products'][$product_id]['campaigns']) as $campaign_id) {
        if (!empty($_POST['products'][$product_id]['campaigns'][$campaign_id]['status'])) {

          if (isset($product->data['campaigns'][$campaign_id])) {
            $product->data['campaigns'][$campaign_id] = array_replace_recursive($product->data['campaigns'][$campaign_id], $_POST['products'][$product_id]['campaigns'][$campaign_id]);
          } else {
            $product->data['campaigns'][uniqid()] = $_POST['products'][$product_id]['campaigns'][$campaign_id];
          }

        } else {
          if (!empty($campaign_id)) unset($product->data['campaigns'][$campaign_id]);
        }
      }

      $product->save();
    }

    notices::add('success', language::translate('success_changes_saved', 'Changes saved'));

    header('Location: '. $_SERVER['REQUEST_URI']);
    exit;
  }

  if (isset($_POST['delete'])) {

    foreach ($_POST['products'] as $product_id) {

      $product = new ent_product($product_id);
      $product->delete();

      notices::add('success', language::translate('success_posts_deleted', 'Posts deleted'));
      header('Location: '. $_SERVER['REQUEST_URI']);
      exit;
    }
  }

  $columns = array(
    'status',
    'id',
    'image',
    'name',
    'code',
    'sku',
    'gtin',
    'manufacturer',
    'supplier',
    'price',
    'campaign',
    'purchase_price',
    'quantity',
    'weight',
    'dimensions',
  );
?>
<h1><?php echo $app_icon; ?> <?php echo language::translate('title_multi_update_products', 'Multi Update Products'); ?></h1>

<?php echo functions::form_draw_form_begin('search_form', 'get'); ?>
  <?php echo functions::form_draw_hidden_field('app', true); ?>
  <?php echo functions::form_draw_hidden_field('doc', true); ?>

  <table class="table">
    <tr>
      <td><?php echo language::translate('title_search', 'Search'); ?><br />
        <?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') .'"'); ?>
      </td>
      <td><?php echo language::translate('title_category', 'Category'); ?><br />
        <?php echo functions::form_draw_categories_list('category_id', true); ?>
      </td>
      <td><?php echo language::translate('title_manufacturer', 'Manufacturer'); ?><br />
        <?php echo functions::form_draw_manufacturers_list('manufacturer_id', true); ?>
      </td>
      <td><?php echo language::translate('title_supplier', 'Supplier'); ?><br />
        <?php echo functions::form_draw_suppliers_list('supplier_id', true); ?>
      </td>
      <td><?php echo language::translate('title_language', 'Language'); ?><br />
        <?php echo functions::form_draw_languages_list('language_code', true); ?>
      </td>
      <td><?php echo language::translate('title_currency', 'Currency'); ?><br />
        <?php echo functions::form_draw_currencies_list('currency_code', true); ?>
      </td>
      <td><br />
        <?php echo functions::form_draw_button('filter', language::translate('title_filter', 'Filter'), 'submit', 'class="btn btn-default btn-block"'); ?>
      </td>
    </tr>
  </table>

  <div class="column-filter form-control">
    <?php echo functions::form_draw_button('apply', language::translate('title_apply', 'Apply'), 'submit', 'style="float: right;"'); ?>
    <?php foreach ($columns as $column) echo '<label class="checkbox">'. functions::form_draw_checkbox('columns[]', $column, true) .' '. $column .'</label>'?>
  </div>
<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::form_draw_form_begin('catalog_form', 'post'); ?>

  <table class="table table-striped data-table" width="100%">
    <thead>
      <tr>
        <th><?php echo functions::form_draw_checkbox('checkbox_toggle', '', ''); ?></th>
        <th data-column="status" style="width: 20px;"></th>
        <th data-column="id" style="width: 50px;"><?php echo language::translate('title_id', 'ID'); ?></th>
        <th data-column="image" style="width: 75px;"></th>
        <th data-column="name"><?php echo language::translate('title_name', 'Name'); ?></th>
        <th data-column="code" style="width: 150px;"><?php echo language::translate('title_code', 'Code'); ?></th>
        <th data-column="sku" style="width: 150px;"><?php echo language::translate('title_sku', 'SKU'); ?></th>
        <th data-column="gtin" style="width: 150px;"><?php echo language::translate('title_gtin', 'GTIN'); ?></th>
        <th data-column="manufacturer"><?php echo language::translate('title_manufacturer', 'Manufacturer'); ?></th>
        <th data-column="supplier"><?php echo language::translate('title_supplier', 'Supplier'); ?></th>
        <th data-column="price" class="text-center" style="width: 100px;"><?php echo language::translate('title_price', 'Price'); ?></th>
        <th data-column="campaign" class="text-center" style="width: 320px;"><?php echo language::translate('title_campaign', 'Campaign'); ?></th>
        <th data-column="purchase_price" class="text-center" style="width: 225px;"><?php echo language::translate('title_purchase_price', 'Purchase Price'); ?></th>
        <th data-column="quantity" class="text-center" style="width: 125px;"><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
        <th data-column="weight" class="text-center" style="width: 150px;"><?php echo language::translate('title_weight', 'Weight'); ?></th>
        <th data-column="dimensions" class="text-center" style="width: 320px;"><?php echo language::translate('title_dimensions', 'Dimensions'); ?></th>
        <th style="width: 50px;">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
<?php
  $num_category_rows = 0;
  $num_product_rows = 0;

  $products_query = database::query(
    "select p.*, pi.name, pp_tmp.price, pc_tmp.id as campaign_id, pc_tmp.campaign_price, pc_tmp.campaign_start_date, pc_tmp.campaign_end_date, if(pos_tmp.product_id, 1, 0) as options_stock
    from ". DB_TABLE_PRODUCTS ." p
    left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
    left join ". DB_TABLE_PRODUCTS_TO_CATEGORIES ." ptc on (p.id = ptc.product_id)
    left join (
      select pp.product_id, ". database::input($_GET['currency_code']) ." as price
      from ". DB_TABLE_PRODUCTS_PRICES ." pp
    ) pp_tmp on (pp_tmp.product_id = p.id)
    left join (
      select pc.id, pc.product_id, pc.". database::input($_GET['currency_code']) ." as campaign_price, pc.start_date as campaign_start_date, pc.end_date as campaign_end_date
      from ". DB_TABLE_PRODUCTS_CAMPAIGNS ." pc
      order by pc.id asc
    ) pc_tmp on (pc_tmp.product_id = p.id)
    left join (
      select pos.product_id
      from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." pos
      group by pos.id
    ) pos_tmp on (pos_tmp.product_id = p.id)
    left join ". DB_TABLE_MANUFACTURERS ." m on (p.manufacturer_id = m.id)
    left join ". DB_TABLE_SUPPLIERS ." s on (p.supplier_id = s.id)
    where p.id
    ". (!empty($_GET['query']) ? "and (p.id = '". database::input($_GET['query']) ."' or pi.name like '%". database::input($_GET['query']) ."%' or p.code like '%". database::input($_GET['query']) ."%' or p.sku like '%". database::input($_GET['query']) ."%' or pi.short_description like '%". database::input($_GET['query']) ."%' or pi.description like '%". database::input($_GET['query']) ."%' or m.name like '%". database::input($_GET['query']) ."%' or s.name like '%". database::input($_GET['query']) ."%')" : false) ."
    ". (!empty($_GET['category_id']) ? "and ptc.category_id = ". (int)$_GET['category_id'] : false) ."
    ". (!empty($_GET['manufacturer_id']) ? "and p.manufacturer_id = ". (int)$_GET['manufacturer_id'] : false) ."
    ". (!empty($_GET['supplier_id']) ? "and p.supplier_id = ". (int)$_GET['supplier_id'] : false) ."
    group by id
    order by pi.name asc;"
  );

  if (database::num_rows($products_query) > 0) {

  // Jump to data for current page
    if ($_GET['page'] > 1) database::seek($products_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($product = database::fetch($products_query)) {
      $num_product_rows++;

      if (!isset($_POST['products'][$product['id']])) {
        $ctrl_product = new ent_product($product['id']);
        $_POST['products'][$product['id']] = $ctrl_product->data;
      }

      if (!empty($product['campaign_id'])) {
        $campaign_index = $product['campaign_id'];
      } else {
        $campaign_index = 'new';
      }

      if (!empty($_POST['products'][$product['id']]['campaigns'][$campaign_index])) {
        $_POST['products'][$product['id']]['campaigns'][$campaign_index]['status'] = 1;
      }
?>
    <tr class="<?php echo empty($product['status']) ? 'semi-transparent' : ''; ?>">
      <td><?php echo functions::form_draw_checkbox('products['. $product['id'] .'][selected]', $product['id']); ?></td>
      <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($product['status']) ? '#99cc66' : '#ff6666') .';"'); ?></td>
      <td><?php echo functions::form_draw_hidden_field('products['. $product['id'] .'][id]', $product['id']); ?><?php echo $product['id']; ?></td>
      <td><?php echo '<img src="'. (!empty($product['image']) ? functions::image_resample(FS_DIR_APP . 'images/' . $product['image'], FS_DIR_APP . 'cache/', 64, 64, 'FIT_USE_WHITESPACING') : WS_DIR_IMAGES .'no_image.png') .'" width="16" height="16" align="absbottom" />'; ?></td>
      <td><?php echo functions::form_draw_text_field('products['. $product['id'] .'][name]['. $_GET['language_code'] .']', true); ?></td>
      <td><?php echo functions::form_draw_text_field('products['. $product['id'] .'][code]', true); ?></td>
      <td><?php echo functions::form_draw_text_field('products['. $product['id'] .'][sku]', true); ?></td>
      <td><?php echo functions::form_draw_text_field('products['. $product['id'] .'][gtin]', true); ?></td>
      <td><?php echo functions::form_draw_manufacturers_list('products['. $product['id'] .'][manufacturer_id]', true); ?></td>
      <td><?php echo functions::form_draw_suppliers_list('products['. $product['id'] .'][supplier_id]', true); ?></td>
      <td><?php echo functions::form_draw_currency_field($_GET['currency_code'], 'products['. $product['id'] .'][prices]['. $_GET['currency_code'] .']', true, 'style="width: 100px;"'); ?></td>
      <td>
        <?php echo functions::form_draw_hidden_field('products['. $product['id'] .'][campaigns]['. $campaign_index .'][id]', ($campaign_index != 'new') ? $campaign_index : ''); ?>
        <div class="input-group">
          <span class="input-group-addon">
            <?php echo functions::form_draw_checkbox('products['. $product['id'] .'][campaigns]['. $campaign_index .'][status]', '1', true); ?>
          </span>
          <?php echo functions::form_draw_currency_field($_GET['currency_code'], 'products['. $product['id'] .'][campaigns]['. $campaign_index .']['. $_GET['currency_code'] .']', true, 'style="width: 100px;"'); ?>
          <?php echo functions::form_draw_date_field('products['. $product['id'] .'][campaigns]['. $campaign_index .'][start_date]', true); ?>
          <?php echo functions::form_draw_date_field('products['. $product['id'] .'][campaigns]['. $campaign_index .'][end_date]', true); ?>
        </div>
      </td>
      <td>
        <div class="input-group">
          <?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][purchase_price]', true, 2, 0, null, 'style="width: 40%;"'); ?>
          <?php echo functions::form_draw_currencies_list('products['. $product['id'] .'][purchase_price_currency_code]', true, false, 'style="width: 60%;"'); ?>
        </div>
      </td>
      <td><?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][quantity]', true, 2, null, null, !empty($product['options_stock']) ? 'disabled="disabled"' : false); ?></td>
      <td>
       <div class="input-group">
          <?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][weight]', true, 4); ?>
          <?php echo functions::form_draw_weight_classes_list('products['. $product['id'] .'][weight_class]', true); ?>
        </div>
      </td>
      <td>
        <div class="input-group" style="width: 320px;">
          <?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][dim_x]', true, 4); ?> x
          <?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][dim_y]', true, 4); ?> x
          <?php echo functions::form_draw_decimal_field('products['. $product['id'] .'][dim_z]', true, 4); ?>
          <?php echo functions::form_draw_length_classes_list('products['. $product['id'] .'][dim_class]', true); ?>
        </div>
      </td>
      <td><a href="<?php echo document::href_link('', array('app' => $_GET['app'], 'doc' => 'edit_product', 'product_id' => $product['id'])); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
    </tr>
<?php
      if (++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }
?>
    </tbody>
    <tfoot>
      <tr class="footer">
        <td colspan="11"><?php echo language::translate('title_products', 'Products'); ?>: <?php echo $num_product_rows; ?></td>
      </tr>
    </tfoot>
  </table>

  <ul class="list-inline">
    <li><?php echo language::translate('text_with_selected', 'With selected'); ?>:</li>
    <li><?php echo functions::form_draw_button('set_price', language::translate('title_set_price', 'Set Price'), 'button'); ?></li>
    <li><?php echo functions::form_draw_button('set_discount', language::translate('title_set_discount', 'Set Discount'), 'button'); ?></li>
    <li><?php echo functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete'); ?></li>
  </ul>

  <div><?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', 'save'); ?></div>

<?php echo functions::form_draw_form_end(); ?>

<?php echo functions::draw_pagination(ceil(database::num_rows($products_query)/settings::get('data_table_rows_per_page'))); ?>

<script>
  $('.data-table tbody tr').off().click(function(e) {
    if ($(e.target).is(':input')) return;
    if ($(e.target).is('a, a *')) return;
    if ($(e.target).is('th')) return;
    $(this).find('input:checkbox').trigger('click');
  });

  $('.column-filter :input').bind('change propertyChange', function(){
    var index = $('.data-table thead th[data-column="'+ $(this).val() +'"]').index() + 1;
    console.log(index);
    if ($(this).prop('checked')) {
      $('.data-table thead th:nth-child('+index+')').show();
      $('.data-table tbody td:nth-child('+index+')').show();
    } else {
      $('.data-table thead th:nth-child('+index+')').hide();
      $('.data-table tbody td:nth-child('+index+')').hide();
    }
  });

  $('.column-filter :input').trigger('change');

  function toggleCampaign(element) {
    if ($(element).is(':checked')) {
      $(element).closest('tr').find('input[name^="products"][name*="campaigns"]').not('input[name$="[id]"]').not('input[name$="[status]"]').removeAttr('disabled');
    } else {
      $(element).closest('tr').find('input[name^="products"][name*="campaigns"]').not('input[name$="[id]"]').not('input[name$="[status]"]').attr('disabled', 'disabled');
    }
  }

  $('input[name^="products"][name*="campaigns"][name$="[status]"]').each(function(){
    toggleCampaign(this);
  });

  $('input[name^="products"][name*="campaigns"][name$="[status]"]').change(function(){
    toggleCampaign(this);
  });

  $('button[name="set_price"]').click(function(){
    var adjustment_value = prompt("<?php echo htmlspecialchars(language::translate('text_enter_amount', 'Enter amount') . ':\n(Syntax: =10, -10, -10%)'); ?>", "").replace(',', '.').replace(' ', '');
    if (adjustment_value == null) return;
    $('input:checkbox[name$="[selected]"]:checked').each(function(){
      var parent = $(this).closest('tr');
      var new_value = Number($(parent).find('input[name^="products"][name*="prices"][name$="[<?php echo $_GET["currency_code"]; ?>]"]').val().replace(/,/, '.'));
      if (adjustment_value.substring(adjustment_value.length -1) == '%') {
        var percentage = adjustment_value.substring(0, adjustment_value.length -1);
        alert(percentage);
        new_value = Number(new_value) + (new_value * percentage / 100);
      } else if (adjustment_value.substring(0, 1) == '=') {
        new_value = adjustment_value.substring(1);
      } else {
        new_value = Number(new_value) + Number(adjustment_value);
      }
      new_value = Number(new_value).toFixed(<?php echo currency::$currencies[$_GET['currency_code']]['decimals']; ?>);
      $(parent).find('input[name^="products"][name*="prices"][name$="[<?php echo $_GET["currency_code"]; ?>]"]').val(new_value);

    });
  });

  $('button[name="set_discount"]').click(function(){
    var discount = prompt("<?php echo htmlspecialchars(language::translate('text_enter_discount', 'Enter discount') . ':\n(Syntax: =10, -10, -10%)'); ?>", "10%").replace(',', '.').replace(' ', '');
    var start_date = prompt("<?php echo htmlspecialchars(language::translate('text_enter_start_date_or_empty', 'Enter start date or leave empty')); ?>:", "").replace(',', '.').replace(' ', '');
    var end_date = prompt("<?php echo htmlspecialchars(language::translate('text_enter_end_date_or_empty', 'Enter end date or leave empty')); ?>:", "").replace(',', '.').replace(' ', '');
    if (discount == null) return;
    if (start_date == null) return;
    if (end_date == null) return;
    $('input:checkbox[name$="[selected]"]:checked').each(function(){
      var parent = $(this).closest('tr');
      var new_value = Number($(parent).find('input[name^="products"][name*="price"][name$="[<?php echo $_GET["currency_code"]; ?>]"]').val().replace(/,/, '.'));
      if (discount.match(/%$/)) {
        var percentage = Number(discount.substring(0, discount.length -1));
        new_value = new_value - (new_value * percentage / 100);
      } else if (discount.substring(0, 1) == '=') {
        new_value = Number(discount.substring(1));
      } else {
        new_value = Number(new_value) + Number(discount);
      }
      new_value = Number(new_value).toFixed(<?php echo currency::$currencies[$_GET['currency_code']]['decimals']; ?>);
      $(parent).find('input[name^="products"][name*="campaigns"][name$="[status]"]').prop("checked", true).trigger('change');
      $(parent).find('input[name^="products"][name*="campaigns"][name$="[<?php echo $_GET["currency_code"]; ?>]"]').val(new_value);
      $(parent).find('input[name^="products"][name*="campaigns"][name$="[start_date]"]').val(start_date);
      $(parent).find('input[name^="products"][name*="campaigns"][name$="[end_date]"]').val(end_date);
    });
  });
</script>