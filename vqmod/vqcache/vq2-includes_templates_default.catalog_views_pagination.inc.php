<ul class="pagination">
  <?php foreach ($items as $item) { ?>
    <?php if ($item['disabled']) { ?>
    <li class="disabled"><span><?php echo $item['title']; ?></span></li>
    <?php } else { ?>
    <li<?php if ($item['active']) echo ' class="active"'; ?>><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
    <?php } ?>
  <?php } ?>
  <br/>
  <br/>

      <?PHP
      $parsed = parse_url($items[0]['link']);
      $url = strtok($items[0]['link'], '?');
      $query = $parsed['query'];
      parse_str($query, $params);
      unset($params['page']);
      unset($params['items_limit']);
      $queryString = http_build_query($params);
      $url = $url.'?'.$queryString; ?>
      <style>

      .label-pagination {
        background-image: none !important;
        border: none !important;
        position: absolute;
      }

      .label-pagination:hover {
        background-image: none !important;
        background-color: #ffffff00 !important;
      }

      </style>
      <li>&nbsp;<span class = "label-pagination"><?php echo language::translate('title_go_to_page', 'Go to Page'); ?></span> </li>
      <li><input class="form-control pagination_number" placeholder="1" style="background-image:url('/images/customize/Gold Brush Dropdown Menu.png'); border-radius: 5px 5px 5px 5px; background-position: center ; width:60px; " ></li>
      <li>&nbsp;<a class="page_go"data-url="<?PHP echo $url;?>" style="margin-left:5px border-radius: 5px 5px 5px 5px;">Go</a>&nbsp;</li>
     

<!--      <li> <span class = "label-pagination "><?php echo language::translate('title_items_per_page', 'Items per Page'); ?></span> </li> -->
      <li><select class="form-control item_per_pages ">
      <?PHP $per_page = settings::get('items_per_page') ;
      if($per_page != 8 && $per_page != 10 && $per_page != 12 && $per_page != 15 && $per_page != 16 && $per_page != 18 && $per_page != 24 && $per_page != 25 && $per_page != 25 && $per_page != 30 && $per_page != 32 && $per_page != 35 && $per_page != 36 && $per_page != 48){
       ?>
       <option value="<?PHP echo $per_page; ?>" selected><?PHP echo $per_page; ?></option>
      <?PHP
      }

      ?>

      <option value="8" <?PHP echo $per_page == 8? "selected":""; ?>>8</option>
      <option value="10" <?PHP echo $per_page == 10? "selected":""; ?>>10</option>
      <option value="12" <?PHP echo $per_page == 12? "selected":""; ?>>12</option>
      <option value="15" <?PHP echo $per_page == 15? "selected":""; ?>>15</option>
      <option value="16" <?PHP echo $per_page == 16? "selected":""; ?>>16</option>
      <option value="18" <?PHP echo $per_page == 18? "selected":""; ?>>18</option>
      <option value="20" <?PHP echo $per_page == 20? "selected":""; ?>>20</option>
      <option value="24" <?PHP echo $per_page == 24? "selected":""; ?>>24</option>
      <option value="25" <?PHP echo $per_page == 25? "selected":""; ?>>25</option>
      <option value="30" <?PHP echo $per_page == 30? "selected":""; ?>>30</option>
      <option value="32" <?PHP echo $per_page == 32? "selected":""; ?>>32</option>
      <option value="35" <?PHP echo $per_page == 35? "selected":""; ?>>35</option>
      <option value="36" <?PHP echo $per_page == 36? "selected":""; ?>>36</option>
      </select></li>

      
</ul>

      <script>
      $('body').on('keyup', '.pagination_number', function(){
        $('a.page_go').prop('href', $('a.page_go').data('url') + '&page=' + $(this).val());
      });
      $('body').on('change', '.item_per_pages', function(){
        $('a.page_go').prop('href', $('a.page_go').data('url') + '&page=' + $('.pagination_number').val() + '&items_limit=' + $(this).val());
        $('a.page_go')[0].click();
      });
      </script>
      

 <script>
//   $('body').on('click', '.pagination a', function(e){
//     e.preventDefault();
//     var container = '#'+$(this).closest('[id]').attr('id');
//     $(container).load($(this).attr('href') + ' ' + container, function(){
//       $(document).scrollTop(1);
//     });
//   });
 </script>
