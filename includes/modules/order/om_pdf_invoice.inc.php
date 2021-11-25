<?php

  class om_pdf_invoice {
    public $id = __CLASS__;
    public $name = 'PDF Invoice';
    public $description = 'Draw a PDF Invoice';
    public $version = '2.0';
    public $author = 'TiM International';
    public $website = 'http://www.tim-international.net';

    function actions() {

      if (empty($this->settings['status'])) return;

      $options = array();

      $options[] = array(
        'id' => 'pdf_invoice',
        'title' => language::translate('title_pdf_invoice', 'PDF Invoice'),
        'function' => 'draw_invoice',
        'target' => '_blank',
      );

      return array(
        'id' => __CLASS__,
        'name' => $this->name,
        'description' => $this->description,
        'actions' => $options,
      );
    }

    public function draw_invoice($order_ids) {

      $pdf = new ent_pdf();
      $pdf->SetMargins(10, 2, 10);
      $pdf->SetAutoPageBreak(true, 2);

      foreach ($order_ids as $order_id) {
        $order = new ent_order($order_id);

        $pdf->AddPage();

      // Logo
        $pdf->Image(FS_DIR_APP . $this->settings['logotype'], 10, 5, null, 23);

      // Draw document title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Text(103, 13, language::translate('title_invoice', 'Invoice'));

      // Today's date
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(9);
        $pdf->MultiCell(190, 6, date(language::$selected['raw_date']), 0, 'R');

      // Draw document info box
        $pdf->SetFillColor(255);
      //  $pdf->draw_rounded_rectangle(100, 15, 100, 15, 2, 'DF');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(103, 21, language::translate('title_order_date', 'Order Date'));
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(103, 26, date(language::$selected['raw_date'], strtotime($order->data['date_created'])));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(153, 21, language::translate('title_customer_id', 'Customer ID'));
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(153, 26, $order->data['customer']['id']);

        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Text(183, 21, language::translate('title_order_ID', 'Order ID'));
        $pdf->Text(183, 21, language::translate('title_invoice_no', 'Invoice No'));
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(183, 26, $order->data['id']);

      // Draw shipping address
        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.2);
        $pdf->SetFillColor(255);
      //  $pdf->draw_rounded_rectangle(10, 35, 85, 40, 2, 'DF');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(13, 40, language::translate('title_shipping_address', 'Shipping Address'));
        $pdf->SetY(45);
        $pdf->Cell(9, null, null);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(80, 5, functions::format_address($order->data['customer']['shipping_address']), 0, 'L');

      // Draw billing address
        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.2);
        $pdf->SetFillColor(255);
       // $pdf->draw_rounded_rectangle(100, 35, 100, 40, 2, 'DF');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(103, 40, language::translate('title_billing_address', 'Billing Address'));
        $pdf->SetY(45);
        $pdf->Cell(100, null, null);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(80, 5, functions::format_address($order->data['customer']), 0, 'L');

      // Draw invoice info
        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.2);
        $pdf->SetFillColor(255);
        $pdf->draw_rounded_rectangle(10, 80, 190, 13, 2, 'DF');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(13, 85, language::translate('title_reference', 'Reference'));
        $pdf->SetX(20);
        $pdf->SetY(82);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(13, 90, substr($order->data['customer']['firstname'], 0, 1) .'. '. $order->data['customer']['lastname']);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(53, 85, language::translate('title_payment_method', 'Payment Method'));
        $pdf->SetX(20);
        $pdf->SetY(82);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(53, 90, $order->data['payment_option']['name']);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(113, 85, language::translate('title_due_date', 'Due Date'));
        $pdf->SetX(20);
        $pdf->SetY(82);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(113, 90, date('Y-m-d', strtotime('+ '. $this->settings['due_days'] .' days')));

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(143, 85, language::translate('title_tax_id', 'Tax ID'));
        $pdf->SetX(20);
        $pdf->SetY(82);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text(143, 90, $order->data['customer']['tax_id']);

      // Order items header
        $cellpos = array(
          1 => array('w' => 11, 'h' => 5),
          2 => array('w' => 22, 'h' => 5),
          3 => array('w' => 68.5, 'h' => 5),
          4 => array('w' => 22, 'h' => 5),
          4 => array('w' => 22, 'h' => 5),
          5 => array('w' => 22, 'h' => 5),
          6 => array('w' => 22, 'h' => 5),
          7 => array('w' => 22, 'h' => 5),
        );

        $pdf->SetFillColor(255); // Gray color filling each Field Name box
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetY(98);
        $pdf->Cell($cellpos[1]['w'], $cellpos[1]['h'], language::translate('title_qty', 'Qty'), 1, 0, 'C', 1);
        $pdf->Cell($cellpos[2]['w'], $cellpos[1]['h'], language::translate('title_sku', 'SKU'), 1, 0, 'L', 1);
        $pdf->Cell($cellpos[3]['w'], $cellpos[1]['h'], language::translate('title_products', 'Products'), 1, 0, 'L', 1);
        $pdf->Cell($cellpos[4]['w'], $cellpos[1]['h'], language::translate('title_price', 'Price'), 1, 0, 'R');
        $pdf->Cell($cellpos[5]['w'], $cellpos[1]['h'], language::translate('title_tax', 'Tax'), 1, 0, 'R');
        $pdf->Cell($cellpos[6]['w'], $cellpos[1]['h'], language::translate('title_excl_tax', 'Excl. Tax'), 1, 0, 'R');
        $pdf->Cell($cellpos[7]['w'], $cellpos[1]['h'], language::translate('title_incl_tax', 'Incl. Tax'), 1, 0, 'R');
        $pdf->Ln();

      // Order items
        $count = 0;
        foreach ($order->data['items'] as $item) {
          $pdf->SetFont('Arial', '', 9);
          $pdf->Cell($cellpos[1]['w'], $cellpos[1]['h'], (float)$item['quantity'], 1, 0, 'C');
          $pdf->Cell($cellpos[2]['w'], $cellpos[2]['h'], $item['sku'], 1, 0, 'L');
          if (strlen($item['name']) > 35 && strlen($item['name']) < 45) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($cellpos[3]['w'], $cellpos[3]['h'], $item['name'], 1, 0, 'L');
          } else if (strlen($item['name']) > 45){
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($cellpos[3]['w'], $cellpos[3]['h'], substr($item['name'], 0, 45) . '...', 1, 0, 'L');
          }  else {
            $pdf->Cell($cellpos[3]['w'], $cellpos[3]['h'], $item['name'], 1, 0, 'L');
          }
          $pdf->SetFont('Arial', '', 9);
          $pdf->Cell($cellpos[4]['w'], $cellpos[4]['h'], currency::format($item['price'], false, $order->data['currency_code'], $order->data['currency_value']), 1, 0, 'R');
          $pdf->Cell($cellpos[5]['w'], $cellpos[5]['h'], currency::format($item['tax'], false, $order->data['currency_code'], $order->data['currency_value']), 1, 0, 'R');
          $pdf->Cell($cellpos[6]['w'], $cellpos[6]['h'], currency::format($item['price'] * $item['quantity'], false, $order->data['currency_code'], $order->data['currency_value']), 1, 0, 'R');
          $pdf->Cell($cellpos[7]['w'], $cellpos[7]['h'], currency::format(($item['price'] + $item['tax']) * $item['quantity'], false, $order->data['currency_code'], $order->data['currency_value']), 1, 0, 'R');
          $pdf->Ln();
        }

        $summary_rows = array(
          array(
            'title' => settings::get('default_display_prices_including_tax') ? language::translate('title_including_tax') : language::translate('title_excluding_tax'),
            'value' => $order->data['tax_total'],
            'tax' => 0,
            'bold' => false,
          ),
          array(
            'title' => language::translate('title_payment_due', 'Payment Due'),
            'value' => $order->data['payment_due'],
            'tax' => 0,
            'bold' => true,
          ),
        );

        $pdf->Ln();
        foreach (array_merge($order->data['order_total'], $summary_rows) as $row) {
          $pdf->SetX(100);
          $temp = substr ($row['title'], 0 , 3);
          if (!empty($row['bold'])) {
            $pdf->SetFont('Arial', 'B', 11);
          } else {
            $pdf->SetFont('Arial', '', 9);
          }
          if (settings::get('default_display_prices_including_tax')) {
            $pdf->Cell(100, 5, strip_tags($row['title']) . ': ' . currency::format($row['value'] + $row['tax'], false, $order->data['currency_code'], $order->data['currency_value']), 0, 0, 'R');
          } else {
            $pdf->Cell(100, 5, strip_tags($row['title']) . ': ' . currency::format($row['value'], false, $order->data['currency_code'], $order->data['currency_value']), 0, 0, 'R');
          }
          $pdf->Ln();
        }

      // Draw comments box
        if (!empty($order->data['comments'])) {
          $comments = array();
          foreach ($order->data['comments'] as $comment) {
            if (!empty($comment['hidden'])) continue;
            $comments[] = $comment['text'];
          }
        }

        if (!empty($comments)) {
          $pdf->SetFillColor(255);
          $pdf->draw_rounded_rectangle(10, 225, 190, 30, 2, 'DF');

          $pdf->SetFont('Arial', 'B', 8);
          $pdf->Text(13, 230, language::translate('title_comments', 'Comments'));

          $pdf->SetY(232);
          $pdf->SetFont('Arial', '', 9);
          $pdf->SetX(12);
          //$pdf->Write(5, implode("\r\n", $comments));
          $pdf->MultiCell(180, 4.5, implode("\r\n", $comments));
        }

      // Footer line
        $pdf->SetLineWidth(0.1);
        $pdf->Line(10, 260, 210-10, 260);

        $pdf->SetFont('Arial', '', 8);

      // Address
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(11, 264, language::translate('title_store_address', 'Store Address'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(80);
        $pdf->SetY(266);
        $pdf->Multicell(40, 4, settings::get('store_postal_address'), 0, 'L');

      // Phone
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(45, 264, language::translate('title_phone', 'Phone'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(45, 269, settings::get('store_phone'), 0, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(45, 276, language::translate(__CLASS__.':title_fax', 'Fax'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(45, 281, $this->settings['fax'], 0, 'L');

      // Financial
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(75, 264, language::translate('title_vat_registration_id', 'VAT Registration ID'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(75, 269, settings::get('store_tax_id'), 0, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(75, 276, language::translate(__CLASS__.':title_bankgiro', 'Bankgirot'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(75, 281, $this->settings['bankgiro'], 0, 'L');

      // Bank
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(110, 264, language::translate(__CLASS__.':title_bic', 'BIC'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(110, 269, $this->settings['bic'], 0, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(110, 276, language::translate(__CLASS__.':title_iban', 'IBAN'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(110, 281, $this->settings['iban'], 0, 'L');

      // Web
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(160, 264, language::translate('title_email', 'Email'));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(160, 269, settings::get('store_email'), 0, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(160, 276, language::translate('title_website', 'Website'));
        $pdf->SetFont('Arial', '', 9);
        //$pdf->Text(130, 281, document::ilink(''), 0, 'L');
        $pdf->Text(160, 281, $_SERVER['SERVER_NAME'], 0, 'L');
      }

    // Output
      ob_end_clean();
      $pdf->Output('Invoice-'.$order->data['id'].'.pdf', 'I');
      //$pdf->Output();
      exit;
    }

    public function before_process() {}

    public function after_process() {}

    function settings() {
      return array(
        array(
          'key' => 'status',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_status', 'Status'),
          'description' => language::translate(__CLASS__.':description_status', 'Enables or disables the module.'),
          'function' => 'toggle("e/d")',
        ),
        array(
          'key' => 'logotype',
          'default_value' => WS_DIR_IMAGES . 'logotype.png',
          'title' => language::translate(__CLASS__.':title_logotype', 'Logotype'),
          'description' => language::translate(__CLASS__.':description_logotype', 'Path to logotype image to be printed.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'due_days',
          'default_value' => '10',
          'title' => language::translate(__CLASS__.':title_due_days', 'Due Days'),
          'description' => language::translate(__CLASS__.':description_due_days', 'The date when the payment must have been last made.'),
          'function' => 'number()',
        ),
        array(
          'key' => 'fax',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_fax', 'Fax'),
          'description' => language::translate(__CLASS__.':description_fax', 'Your fax number.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'bankgiro',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_bankgiro', 'Bankgirot'),
          'description' => language::translate(__CLASS__.':description_bankgirot', 'Your bankgiro account.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'bic',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_bic', 'BIC'),
          'description' => language::translate(__CLASS__.':description_bic', 'Your bank\'s BIC code.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'iban',
          'default_value' => '',
          'title' => language::translate(__CLASS__.':title_iban', 'IBAN'),
          'description' => language::translate(__CLASS__.':description_iban', 'Your IBAN account number.'),
          'function' => 'text()',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module by the given priority value.'),
          'function' => 'number()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }
