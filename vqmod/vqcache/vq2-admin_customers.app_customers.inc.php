<?php
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;


      if (empty($_GET['sort'])) $_GET['sort'] = 'date_created';

      document::$snippets['title'][] = language::translate('title_customers', 'Customers');

      breadcrumbs::add(language::translate('title_customers', 'Customers'));
      

			if(isset($_POST['save']) && isset($_POST['vip'])){
			try {
			foreach ($_POST['vip'] as $customer_id => $value) {
			$customer = new ent_customer($customer_id);
            $customer->data['vip'] = $value;
            $customer->save();
      }
      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link());
      exit;
      } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
      }
      }
      
  
          if (isset($_POST['enable']) || isset($_POST['disable']) || isset($_POST['delete'])) {
      

    try {
      if (empty($_POST['customers'])) throw new Exception(language::translate('error_must_select_customers', 'You must select customers'));

      foreach ($_POST['customers'] as $customer_id) {
        $customer = new ent_customer($customer_id);

      if(!empty($_POST['delete'])){
            $customer->delete();
        } else {
      
        $customer->data['status'] = !empty($_POST['enable']) ? 1 : 0;
        $customer->save();
      }

		  }
      

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link());
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

// Table Rows

     if (!empty($_POST['set_customer_groups'])) {
       try {
           if (empty($_POST['customers'])) throw new Exception(language::translate('error_must_select_customer', 'You must select a customer to perform the operation'));
           foreach ($_POST['customers'] as $customer_id) {
               $customer = new ctrl_customer($customer_id);
               $customer->data['customer_group_id'] = $_POST['customer_group_id'];
               $customer->save();
           }
           notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
       } catch(Exception $e) {
           notices::$data['errors'][] = $e->getMessage();
       }
   }
      
  $customers = array();

  if (!empty($_GET['query'])) {
    
    $sql_find = array(
      "c.id = '". database::input($_GET['query']) ."'",
      "c.email like '%". database::input($_GET['query']) ."%'",
      "c.tax_id like '%". database::input($_GET['query']) ."%'",
      "c.company like '%". database::input($_GET['query']) ."%'",
      "c.phone like '%". database::input($_GET['query']) ."%'",
      "concat(c.firstname, ' ', c.lastname) like '%". database::input($_GET['query']) ."%'",

          "date_valid_from like '%". database::input($_GET['query']) ."%'",
          "date_valid_to like '%". database::input($_GET['query']) ."%'",
          "discount_code_date_valid_from like '%". database::input($_GET['query']) ."%'",
          "discount_code_date_valid_to like '%". database::input($_GET['query']) ."%'",
      
      "cg.name like '%". database::input($_GET['query']) ."%'",
    );
      






  }


  switch($_GET['sort']) {
    case 'id_asc':
      $sql_sort = "c.id asc";
      break;
    case 'email':
      $sql_sort = "c.email";
      break;
    case 'name':
      $sql_sort = "c.firstname, c.lastname";
      break;
    case 'total_sales_desc':
      $sql_sort = "oc.total_sales desc";
      break;
      case 'total_sales_asc':
      $sql_sort = "oc.total_sales asc";
      break;
    case 'company':
      $sql_sort = "c.firstname, c.lastname";
      break;
    default:
      $sql_sort = "c.date_created desc, c.id desc";
      break;
  }
      
  $customers_query = database::query(
    
    "select c.*, oc.total_sales , cg.name as customer_group_name from ". DB_TABLE_CUSTOMERS ." c
    left join ". DB_TABLE_CUSTOMER_GROUPS ." cg on (c.customer_group_id = cg.id)
    left join (
           select o.customer_id , count(o.id) as total_count, sum(oi.total_sales) as total_sales
           from ". DB_TABLE_ORDERS ." o
           left join (
             select order_id, sum(price * quantity) as total_sales from ". DB_TABLE_ORDERS_ITEMS ."
             group by order_id
           ) oi on (oi.order_id = o.id)
           where o.order_status_id in (
            select id from ". DB_TABLE_ORDER_STATUSES ."
            where is_sale
           ) 
           group by o.customer_id
    ) oc on (oc.customer_id = c.id)
      
    where c.id
    ". (!empty($sql_find) ? "and (". implode(" or ", $sql_find) .")" : "") ."
    
      order by $sql_sort;"
      
  );

  
      if ($_GET['page'] > 1) database::seek($customers_query, settings::get('data_table_rows_per_page') * ($_GET['page'] - 1));
      

  $page_items = 0;
  while ($customer = database::fetch($customers_query)) {
    $customers[] = $customer;
    if (++$page_items == settings::get('data_table_rows_per_page')) break;
  }

// Number of Rows
  $num_rows = database::num_rows($customers_query);

// Pagination
  $num_pages = ceil($num_rows/settings::get('data_table_rows_per_page'));
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_customers', 'Customers'); ?>
  </div>

  <div class="panel-action">
    <ul class="list-inline">
      <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('doc' => 'edit_customer'), true), language::translate('title_add_new_customer', 'Add New Customer'), '', 'add'); ?></li>
    </ul>
  </div>

  <?php echo functions::form_draw_form_begin('search_form', 'get'); ?>
    <?php echo functions::form_draw_hidden_field('app', true); ?>
    <?php echo functions::form_draw_hidden_field('doc', true); ?>
    <div class="panel-filter">
      <div class="expandable"><?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_phrase_or_keyword', 'Search phrase or keyword') .'"'); ?></div>
      <div><?php echo functions::form_draw_button('filter', language::translate('title_search', 'Search'), 'submit'); ?></div>
    </div>
  <?php echo functions::form_draw_form_end(); ?>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('customers_form', 'post'); ?>

      
      <table class="table table-striped table-hover table-sortable data-table">
      
        <thead>
          <tr>
            <th><?php echo functions::draw_fonticon('fa-check-square-o fa-fw checkbox-toggle', 'data-toggle="checkbox-toggle"'); ?></th>
            <th></th>
            
            <th data-sort="id"><?php echo language::translate('title_id', 'ID'); ?></th>
            <th data-sort="email"><?php echo language::translate('title_name_email', 'Name / Email'); ?></th>
            <th class="text-center"><?php echo language::translate('title_order_history', 'Order History'); ?></td>
	        <th class="text-left"><?php echo language::translate('title_Notes', 'Notes'); ?></th>		
            <th class="text-center"><?php echo language::translate('title_rank', 'Rank'); ?></th>
            <th data-sort="<?php echo $_GET['sort']==='total_sales_desc'? 'total_sales_asc' : 'total_sales_desc'; ?>" class="text-center"><?php echo language::translate('title_total_sales', 'Total Sales'); ?></th>
            <th class="text-center"><?php echo language::translate('title_attempts', 'Attempts'); ?></th>

        <th class="text-center"><?php echo language::translate('title_customer_group', 'Customer Group'); ?></th>
      

          <th class="text-center">Date valid from</th>
          <th class="text-center">Date valid to</th>
      
            <th data-sort="date_created" class="text-center"><?php echo language::translate('title_date_registered', 'Date Registered'); ?></th>      
      





          </tr>
        </thead>

        <tbody>
          <?php foreach ($customers as $customer) { ?>
          <tr class="<?php echo empty($customer['status']) ? 'semi-transparent' : null; ?>">
            <td><?php echo functions::form_draw_checkbox('customers['.$customer['id'].']', $customer['id']); ?></td>
            <td><?php echo functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($customer['status']) ? '#88cc44' : '#ff6644') .';"'); ?></td>
            <td><?php echo $customer['id']; ?></td>

       <td>
       <strong><a href="<?php echo document::href_link('', array('doc' => 'edit_customer', 'customer_id' => $customer['id']), true); ?>">
       <span style="color:#ffb83d"><?php echo $customer['firstname'] .' '. $customer['lastname']; ?></span></strong></br>
       <span style="color:#4dfa67"><?php echo $customer['email']; ?></br>
       <span style="color:#ffb83d"><?php echo $customer['phone']; ?></span></br>
       <span style="color:#4dfa67"><?php echo $customer['company']; ?></span>
       </div>
       </td>
       
      
            
      
      

            
	  	
      <td class="text-center"><a href="<?php echo document::href_ilink('order_history',array('customerId' => $customer['id'])); ?>" data-toggle="lightbox">view</a></br><span style="color:#fff"><?php echo !empty($total_Order_number)? 'Total Order: '.$total_Order_number: "Suspected Spy"; ?></br><span style="color:#fff"><?php echo $customer['genuine']; ?></span></td>
      <td class="text-left"><span style="color:#fff"><?php echo nl2br ($customer['notes']); ?></span></td>
      <td class="text-center"><?php echo '<input type="hidden" name="'. htmlspecialchars('vip['.$customer['id'].']') .'" value="0" /><input type="checkbox" name="'. htmlspecialchars('vip['.$customer['id'].']') .'" value="1" '. ('1' == $customer['vip'] ? ' checked="checked"' : false) .' />'; ?> <?php echo language::translate('title_vip', 'VIP'); ?></td>
      <td class="text-center"><?php echo currency::format(!empty($customer['total_sales']) ? $customer['total_sales'] : 0, false, settings::get('store_currency_code')); ?></td>

      

      <td class="text-center"><?php echo $customer['attempts']; ?></td>
      

        <th class="text-center"><?php echo $customer['customer_group_name']; ?></td>
      

          <th class="text-center"><?php echo $customer['date_valid_from']; ?></td>
          <th class="text-center"><?php echo $customer['date_valid_to']; ?></td>
      
            <td class="text-right"><?php echo language::strftime(language::$selected['format_datetime'], strtotime($customer['date_created'])); ?></td>
            <td class="text-right"><a href="<?php echo document::href_link('', array('doc' => 'edit_customer', 'customer_id' => $customer['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
          </tr>
          <?php } ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="8"><?php echo language::translate('title_customers', 'Customers'); ?>: <?php echo $num_rows; ?></td>

      <td></td>
      
          </tr>
        </tfoot>
      </table>


      <fieldset>
        <legend><?php echo language::translate('title_customer_status', 'Customer Status'); ?></legend>
            <div class="input-group">
                <?php echo functions::form_draw_customer_groups_list('customer_group_id', true); ?>
                <span class="input-group-btn">
            <button class="btn btn-default" name="set_customer_groups" value="true" type="submit" formtarget="_self">
            <?php echo language::translate('title_set', 'Set'); ?></button>
          </span>
        </div>
      </fieldset>
      </br>
      
      <div class="btn-group">
        <?php echo functions::form_draw_button('enable', language::translate('title_enable', 'Enable'), 'submit', '', 'on'); ?>
        
		  <?php echo functions::form_draw_button('disable', language::translate('title_disable', 'Disable'), 'submit', '', 'off'); ?>
		  <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
		  </div>
		  <div class="pull-right">
          <?php echo functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', '', 'delete'); ?>
          </div>
      


    <?php echo functions::form_draw_form_end(); ?>
  </div>

  <div class="panel-footer">
    <?php echo functions::draw_pagination($num_pages); ?>

    <script>
        $('select[name="customer_group_id"] option[value=""]').text('-- <?php echo language::translate('title_customer_groups', ''); ?> --');

        $('.data-table input[name^="customers["]').change(function() {
            if ($('.data-table input[name^="customers["]:checked').length > 0) {
                $('#order-actions>li button').removeAttr('disabled');
            } else {
             $('#order-actions>li button').attr('disabled', 'disabled');
            }
        }).trigger('change');

    </script>
      
  </div>
</div>
