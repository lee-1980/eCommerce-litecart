<?php
  $settings_json = json_encode(array(
    'fetch' => array(
      'fetch-product-category' => isset($_POST['fetch-product-category']) ? boolval($_POST['fetch-product-category']) : true,
      'search-hit-name' => isset($_POST['search-hit-name']) ? boolval($_POST['search-hit-name']) : true,
      'search-hit-short-descr' => isset($_POST['search-hit-short-descr']) ? boolval($_POST['search-hit-short-descr']) : true,
      'search-hit-descr' => isset($_POST['search-hit-descr']) ? boolval($_POST['search-hit-descr']) : false,
      'search-hit-keywords' => isset($_POST['search-hit-keywords']) ? boolval($_POST['search-hit-keywords']) : false,
      'search-hit-manufacturers' => isset($_POST['search-hit-manufacturers']) ? boolval($_POST['search-hit-manufacturers']) : true,
      'search-hit-suppliers' => isset($_POST['search-hit-suppliers']) ? boolval($_POST['search-hit-suppliers']) : true,
    ),
    'js' => array(
      'result_amount' => isset($_POST['result_amount']) ? intval($_POST['result_amount']) : 6,
      'padding' => isset($_POST['padding']) ? intval($_POST['padding']) : 5,
      'result_row1' => isset($_POST['result_row1']) ? $_POST['result_row1'] : 'name',
      'result_row2' => isset($_POST['result_row2']) ? $_POST['result_row2'] : false,
      'shouldSort' => isset($_POST['shouldSort']) ? boolval($_POST['shouldSort']) : true,
      'threshold' => isset($_POST['threshold']) ? floatval($_POST['threshold']) : 0.6,
      'location' => isset($_POST['location']) ? intval($_POST['location']) : 0,
      'distance' => isset($_POST['distance']) ? intval($_POST['distance']) : 100,
      'maxPatternLength' => isset($_POST['maxPatternLength']) ? intval($_POST['maxPatternLength']) : 32,
      'width' => isset($_POST['width']) ? intval($_POST['width']) : 0,
      'keys' => isset($_POST['keys']) ? explode(',',$_POST['keys']) : array( 
        0 => 'name',
        1 => 'keywords',
        2 => 'sdescr',
        3 => 'descr',
        4 => 'manufacturer',
        5 => 'supplier',
      ),
    ),
  ));    

  if(!empty($_POST['save'])) 
  {
    database::query('UPDATE `'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'eyecandy_modules_settings` SET `settings_json` = \''.$settings_json.'\' WHERE `module_key` = "PRODUCTSEARCH";');
    notices::add('success', language::translate('success_changes_saved', 'Changes were successfully saved.'));
    header('Location: '. document::link('', array(), true, array('action')));

    exit;
  }
  else
  {
    $settings_query = database::query('SELECT * FROM `'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'eyecandy_modules_settings` WHERE `module_key` = "PRODUCTSEARCH";');

    if(database::num_rows($settings_query) == 0) 
    {
      $settings_query = database::query('INSERT INTO `'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'eyecandy_modules_settings` (`module_key`, `settings_json`) VALUES ("PRODUCTSEARCH", \''.$settings_json.'\');');
      $settings = $settings_json;
    }
    else
    {
      $settings = database::fetch($settings_query)['settings_json'];
    }
  }

  $settings = json_decode($settings);
?>

<h1><?php echo $app_icon; ?> <?php echo language::translate('title_product_search_settings', 'Product Search Settings'); ?></h1>

<?php 
  echo functions::form_draw_form_begin('productsearch_settings_form', 'post', null, false, 'style="max-width: 960px;"');

  $ava = [
    "name",
    "keywords",
    "sdescr",
    "descr",
    "manufacturer",
    "supplier",
    "category",
    "category_desc"
  ];

  $avaMap = [
    "name" => "Product name",
    "keywords" => "Product keywords",
    "sdescr" => "Product short description",
    "descr" => "Product long description",
    "manufacturer" => "Manufacturer",
    "supplier" => "Supplier",
    "category" => "Category name",
    "category_desc" => "Product category short description",
  ];

?>

<h3>Fetch settings</h3>
<input <?= intval($settings->fetch->{'fetch-product-category'}) ? 'checked' : ''; ?> type="checkbox" id="fetch-product-category" name="fetch-product-category" value="<?=intval($settings->fetch->{'fetch-product-category'});?>">
<label for="fetch-product-category"> Fetch product category (needed for text in search items when displaying "Product category short description" and "Category name" )</label><br>

<h3>Search hit settings</h3>
<input <?= intval($settings->fetch->{'search-hit-name'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-name" name="search-hit-name" value="<?=intval($settings->fetch->{'search-hit-name'});?>">
<label for="search-hit-name"> Search hit on product name</label><br>

<input <?= intval($settings->fetch->{'search-hit-short-descr'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-short-descr" name="search-hit-short-descr" value="<?=intval($settings->fetch->{'search-hit-short-descr'});?>">
<label for="search-hit-short-descr"> Search hit on product short description</label><br>

<input <?= intval($settings->fetch->{'search-hit-descr'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-descr" name="search-hit-descr" value="<?=intval($settings->fetch->{'search-hit-descr'});?>">
<label for="search-hit-descr"> Search hit on product long description</label><br>

<input <?= intval($settings->fetch->{'search-hit-keywords'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-keywords" name="search-hit-keywords" value="<?=intval($settings->fetch->{'search-hit-keywords'});?>">
<label for="search-hit-keywords"> Search hit on product keywords</label><br>
              
<input <?= intval($settings->fetch->{'search-hit-manufacturers'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-manufacturers" name="search-hit-manufacturers" value="<?=intval($settings->fetch->{'search-hit-manufacturers'});?>">
<label for="search-hit-manufacturers"> Search hit on manufacturers</label><br>

<input <?= intval($settings->fetch->{'search-hit-suppliers'}) ? 'checked' : ''; ?> type="checkbox" id="search-hit-suppliers" name="search-hit-suppliers" value="<?=intval($settings->fetch->{'search-hit-suppliers'});?>">
<label for="search-hit-suppliers"> Search hit on suppliers</label><br><br>

<h3>Search view settings</h3>
<label for="result_amount">Amount of hits shown i list (between 1 and 15):</label>
<input type="number" id="result_amount" name="result_amount" min="1" max="15" value="<?=$settings->js->{'result_amount'}?>"><br><br>

<h3>Display settings</h3>
<label for="padding">Padding (between 1 and 30):</label>
<input type="number" id="padding" name="padding" min="1" max="30" value="<?=$settings->js->{'padding'}?>"><br><br>

<label for="width"><b>List width</b> (0 is the size of the search box, 1-250 something is shorter than the search box (between 0 and 2000):</label>
<input type="number" id="width" name="width" min="0" max="2000" value="<?=$settings->js->{'width'}?>"><br><br>

<label for="result_row1">First text row for list item:</label>
<select name="result_row1" id="result_row1" value="<?=$settings->js->{'result_row1'}?>">
  <?php
    foreach($ava as $value) 
    {
      if($settings->js->{'result_row1'} == $value)
      {
        echo('<option selected value="'.$value.'">'.$avaMap[$value].'</option>');
      }
      else
      {
        echo('<option value="'.$value.'">'.$avaMap[$value].'</option>');
      }
    }
  ?>
</select>
<br><br>

<label for="result_row2">Second text row for list item:</label>
<select name="result_row2" id="result_row2" value="<?=$settings->js->{'keys'}?>">
  <?php
    if($settings->js->{'result_row2'} == false)
    {
      echo('<option selected value="false">None</option>');
    }
    else
    {
      echo('<option value="false">None</option>');
    }
    
    foreach($ava as $value) 
    {
      if($settings->js->{'result_row2'} == $value)
      {
        echo('<option selected value="'.$value.'">'.$avaMap[$value].'</option>');
      }
      else
      {
        echo('<option value="'.$value.'">'.$avaMap[$value].'</option>');
      }
    }
  ?>
</select>
<br><br>

<h3>Smart search settings</h3>
<input <?= intval($settings->js->{'shouldSort'}) ? 'checked' : ''; ?> type="checkbox" id="shouldSort" name="shouldSort" value="<?=intval($settings->js->{'shouldSort'});?>">
<label for="shouldSort"><b>Automatic sorting</b> Whether to sort the result list, by score</label><br><br>

<label for="threshold">Search score hit threshold (decimal between 0 and 1):</label>
<input type="number" id="threshold" name="threshold" step="0.1" min="0" max="1" value="<?=$settings->js->{'threshold'}?>"><br><br>

<label for="location">Determines approximately where in the text is the pattern expected to be found:</label>
<input type="number" id="location" name="location" min="0" max="100" value="<?=$settings->js->{'location'}?>"><br><br>

<label for="distance">Determines how close the match must be to the fuzzy location (specified by location). An exact letter match which is distance characters away from the fuzzy location would score as a complete mismatch:</label>
<input type="number" id="distance" name="distance" min="1" max="100" value="<?=$settings->js->{'distance'}?>"><br><br>

<label for="maxPatternLength">Max pattern length (don't change if you don't know what you are doing, deafult 32):</label>
<input type="number" id="maxPatternLength" name="maxPatternLength" min="1" max="100" value="<?=$settings->js->{'maxPatternLength'}?>"><br><br>

<label for="keys">Keys in order for search hit priorities:</label>
<input size="112" type="text" id="keys" name="keys" value="<?=implode(',',$settings->js->{'keys'});?>"><br><br>

<table class="table table-striped data-table">
  <tbody>
    <tr>
      <td class="text-right">
        <div class="btn-group">
          <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
          <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
        </div>
      </td>
    </tr>
  </tbody>
</table>

<?php echo functions::form_draw_form_end(); ?>