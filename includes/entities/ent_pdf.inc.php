<?php

  require_once(FS_DIR_APP . 'ext/fpdf/fpdf.php');
  define(FS_DIR_APP . 'ext/FPDF_FONTPATH', 'fpdf/font/');

  class ent_pdf extends fpdf {

    public function Text($x, $y, $txt) {
      $txt = mb_convert_encoding($txt, 'Windows-1252', language::$selected['charset']);
      //$txt = iconv($txt, language::$selected['charset'], 'Windows-1252');
      return parent::Text($x, $y, $txt);
    }

    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
      $txt = mb_convert_encoding($txt, 'Windows-1252', language::$selected['charset']);
      //$txt = iconv($txt, language::$selected['charset'], 'Windows-1252');
      return parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    public function draw_rounded_rectangle($x, $y, $w, $h, $r, $style = '') {

      $k = $this->k;
      $hp = $this->h;
      if ($style=='F') {
        $op='f';
      } elseif ($style=='FD' or $style=='DF') {
        $op='B';
      } else {
        $op='S';
      }

      $MyArc = 4/3 * (sqrt(2) - 1);
      $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));
      $xc = $x+$w-$r ;
      $yc = $y+$r;
      $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));

      $this->_arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
      $xc = $x + $w - $r ;
      $yc = $y + $h - $r;
      $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
      $this->_arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
      $xc = $x + $r ;
      $yc = $y + $h - $r;
      $this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - ($y + $h)) * $k));
      $this->_arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
      $xc = $x + $r ;
      $yc = $y + $r;
      $this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $yc) * $k));
      $this->_arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
      $this->_out($op);
    }

    private function _arc($x1, $y1, $x2, $y2, $x3, $y3) {
      $h = $this->h;
      $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }
  }
