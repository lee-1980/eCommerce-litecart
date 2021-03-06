<style>
.logotype {
  max-width: 320px;
  max-height: 70px;
}

h1 {
  margin: 0;
  border: none;
}

.addresses .row > *:nth-child(1), .addresses .row > *:nth-child(2) {
  margin-top: 4mm;
}

.billing-address .rounded-rectangle {
  border: 1px solid #000;
  border-radius: 5mm;
  padding: 4mm;
  margin-left: -15px;
  margin-bottom: 3mm;
}
.billing-address .value {
  margin: 0 !important;
}

.items tr th:last-child, .order-total tr td:last-child {
  width: 30mm;
}

.page .label {
  font-weight: bold;
  margin-bottom: 3pt;
}
.page .value {
  margin-bottom: 5mm;
}
.page .footer .row {
  margin-bottom: 0;
}
</style>

<section class="page" data-size="A4">
  <header class="header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="row">
      <div class="col-xs-6">
        <img class="logotype" src="<?php echo document::link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" />
      </div>

      <div class="col-xs-6 text-right">
        <h1><?php echo language::translate('title_order_copy', 'Order Copy'); ?></h1>
        
      <span style="color: darkblue; font-size: large;"> <div><?php echo language::translate('title_order', 'Order'); ?> No: <?php echo  $order['id']; ?></span></div>
      <span style="color: black; font-size: large;"><div><?php echo !empty($order['date_created']) ? date(language::$selected['raw_date'], strtotime($order['date_created'])) : date(language::$selected['raw_date']); ?></div>
      


    </div>
  </header>

  
  <div class="content">
    <div class="addresses">
      <div class="row">
        <div class="col-xs-6 shipping-address">
            </br>
          <div class="label"><?php echo language::translate('title_shipping_address', 'Shipping Address'); ?></div>
            <div class="value"><?php echo nl2br(reference::country($order['customer']['shipping_address']['country_code'])->format_address($order['customer']['shipping_address'])); ?></br>
              <?php echo !empty($order['customer']['shipping_address']['phone']) ? $order['customer']['shipping_address']['phone'] : $order['customer']['phone']; ?></br>
            <?php echo !empty($order['customer']['email']) ? $order['customer']['email'] : '-'; ?>
      </div>
</div>


        <div class="col-xs-6 billing-address">
          <div class="rounded-rectangle">
            <div class="label"><?php echo language::translate('title_billing_address', 'Billing Address'); ?></div>
             <div class="value"><?php echo nl2br(reference::country($order['customer']['country_code'])->format_address($order['customer'])); ?></br>
            <?php echo !empty($order['customer']['phone']) ? $order['customer']['phone'] : '-'; ?></br>
          <?php echo !empty($order['customer']['email']) ? $order['customer']['email'] : '-'; ?>
      </div>
        </div>
      </div>
    </div>
      



















    </div>

    <div class="row">
      <div class="col-xs-6">
        <div class="label"><?php echo language::translate('title_shipping_option', 'Shipping Option'); ?></div>
        <div class="value"><?php echo !empty($order['shipping_option']['name']) ? $order['shipping_option']['name'] : '-'; ?></div>

        <div class="label"><?php echo language::translate('title_shipping_tracking_id', 'Shipping Tracking ID'); ?></div>
        <div class="value"><?php echo !empty($order['shipping_tracking_id']) ? $order['shipping_tracking_id'] : '-'; ?></div>
      </div>

      <div class="col-xs-6">
        
        <div class="label"><?php echo str_repeat("&nbsp;", 18); ?><?php echo language::translate('title_payment_option', 'Payment Option'); ?></div>
        <div class="value"><?php echo str_repeat("&nbsp;", 18); ?><?php echo !empty($order['payment_option']['name']) ? $order['payment_option']['name'] : '-'; ?></div>

        <div class="label"><?php echo str_repeat("&nbsp;", 18); ?><?php echo language::translate('title_transaction_number', 'Transaction Number'); ?></div>
        <div class="value"><?php echo str_repeat("&nbsp;", 18); ?><?php echo !empty($order['payment_transaction_id']) ? $order['payment_transaction_id'] : '-'; ?></div>
      




      </div>
    </div>

    
      <table class="items table_1 table-striped data-table">
      
      <thead>
        <tr>
          <th><?php echo language::translate('title_qty', 'Qty'); ?></th>
          
      <th></th>
      <th class="main"><?php echo language::translate('title_description', 'Description'); ?></th>
      <th class="text-right"><?php echo language::translate('title_unit_price', 'Price/pcs'); ?></th>
      
      <th class="text-right"></th>
      
      <th class="text-right"><?php echo language::translate('title_total', 'Total'); ?></th>      
      





      </thead>
      <tbody>
        <?php foreach ($order['items'] as $item) { ?>
        <tr>
          <td><?php echo (float)$item['quantity']; ?></td>
          
	   <td></td>		
       <td style="white-space: normal;"><?php echo $item['name']; ?>
       </br>
       <?php echo $item['sku']; ?>     
      

<?php
    if (!empty($item['options'])) {
      foreach ($item['options'] as $key => $value) {
        if (is_array($value)) {
          echo '<br />- '.$key .': ';
          $useComa = false;
          foreach ($value as $v) {
            if ($useComa) echo ', ';
            echo $v;
            $useComa = true;
          }
        } else {
          echo '<br />- '.$key .': '. $value;
        }
      }
    }
?>
          </td>
          <?php if (!empty($order['display_prices_including_tax'])) { ?>
          <td class="text-right"><?php echo currency::format($item['price'] + $item['tax'], false, $order['currency_code'], $order['currency_value']); ?></td>
          
      <td class="text-right"><?php echo str_repeat("&nbsp;", 6); ?></td>
      
          <td class="text-right"><?php echo currency::format($item['quantity'] * ($item['price'] + $item['tax']), false, $order['currency_code'], $order['currency_value']); ?></td>
          <?php } else { ?>
          <td class="text-right"><?php echo currency::format($item['price'], false, $order['currency_code'], $order['currency_value']); ?></td>
          
      <td class="text-right"><?php echo str_repeat("&nbsp;", 6); ?></td>
      
          <td class="text-right"><?php echo currency::format($item['quantity'] * $item['price'], false, $order['currency_code'], $order['currency_value']); ?></td>
          <?php } ?>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    
      <table class="order-total table_1 data-table">
      
      <tbody>
        <?php foreach ($order['order_total'] as $ot_row) { ?>
        <?php if (!empty($order['display_prices_including_tax'])) { ?>
        <tr>
          <td class="text-right"><?php echo $ot_row['title']; ?>:</td>
          <td class="text-right"><?php echo currency::format($ot_row['value'] + $ot_row['tax'], false, $order['currency_code'], $order['currency_value']); ?></td>
        </tr>
        <?php } else { ?>
        <tr>
          <td class="text-right"><?php echo $ot_row['title']; ?>:</td>
          <td class="text-right"><?php echo currency::format($ot_row['value'], false, $order['currency_code'], $order['currency_value']); ?></td>
        </tr>
        <?php } ?>
        <?php } ?>

        <?php if (!empty($order['tax_total']) && $order['tax_total'] != 0) { ?>
        <tr>
          <td class="text-right"><?php echo !empty($order['display_prices_including_tax']) ? language::translate('title_including_tax', 'Including Tax') : language::translate('title_excluding_tax', 'Excluding Tax'); ?>:</td>
          <td class="text-right"><?php echo currency::format($order['tax_total'], false, $order['currency_code'], $order['currency_value']); ?></td>
        </tr>
        <?php } ?>

        <tr>
          <td class="text-right"><strong><?php echo language::translate('title_grand_total', 'Grand Total'); ?>:</strong></td>
          
        <td class="text-right"><strong><?php echo currency::format($order['order_original_grandtotal'], false, $order['currency_code'], $order['currency_value']); ?></strong></td>
      
        </tr>

        <tr>
          <td class="text-right"><strong><?php echo language::translate('title_outstanding:', 'Outstanding'); ?>:</strong></td>
          <td class="text-right"><strong><?php echo currency::format($order['payment_due'], false, $order['currency_code'], $order['currency_value']); ?></strong></td>
        </tr>
      
      </tbody>
    </table>
  </div>

  <?php if (count($order['items']) <= 10) { ?>
  <footer class="footer">

    <hr />

    <div class="row">
      <div class="col-xs-3">
        <div class="label"><?php echo language::translate('title_address', 'Address'); ?></div>
        <div class="value"><?php echo nl2br(settings::get('store_postal_address')); ?></div>
      </div>

      <div class="col-xs-3">
        <?php if (settings::get('store_phone')) { ?>
        <div class="label"><?php echo language::translate('title_phone', 'Phone'); ?></div>
        
	  <div class="value"><?php echo settings::get('store_phone'); ?>
	  +6012 392 5533 - Jansen
	  </br>
      +6016 357 6838 - David
      </br>
      </br>
	  Mon - Fri : 12.00pm - 5.30pm
	  </br>
      Sat - Sun : 12.00pm - 6.00pm      
	  </div>
	  
      
        <?php } ?>

        <?php if (settings::get('store_tax_id')) { ?>
        <div class="label"><?php echo language::translate('title_vat_registration_id', 'VAT Registration ID'); ?></div>
        <div class="value"><?php echo settings::get('store_tax_id'); ?></div>
        <?php } ?>
      </div>

      <div class="col-xs-3">
        <div class="label"><?php echo language::translate('title_email', 'Email'); ?></div>
        <div class="value"><?php echo settings::get('store_email'); ?></div>

        <div class="label"><?php echo language::translate('title_website', 'Website'); ?></div>
        
        <div class="value"><?php echo document::ilink(''); ?></div>
      </div>
      <div class="col-xs-3">
        <div class="label"><?php echo language::translate('title_bank_details', 'Bank Details'); ?></div>
        <div class="value">Name : Lee Jee Szes</br>
        <div class="value">Bank : Maybank</br>
        <div class="value">Account : 1144 8600 6334</br>
      </div>     
	  
      


      <div class="col-xs-3">
      </div>
    </div>
  </footer>
  <?php } ?>
</section>