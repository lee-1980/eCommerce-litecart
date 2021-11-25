<?php

// Remove some history
  database::query(
    "delete from ". DB_TABLE_VISITORS ."
    where date_updated < '". date('Y-m-d 00:00:00', strtotime('-30 days')) ."';"
  );

  document::$snippets['head_tags']['chartist'] = '<link rel="stylesheet" href="'. WS_DIR_EXT .'chartist/chartist.min.css" />' . PHP_EOL
                                               . '<script src="'. WS_DIR_EXT .'chartist/chartist.min.js"></script>';
?>
<div class="widget">
<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title"><?php echo language::translate('title_visits', 'Visits'); ?></h2>
  </div>

  <div class="panel-body">
    <div id="chart-visits" style="height: 200px;" title="<?php echo language::translate('title_visits', 'Visits'); ?>"></div>

    <div style="max-height: 300px; overflow: auto;">
      <table class="table table-striped data-table">
        <thead>
          <tr>
            <th></th>
            <th class="main"><?php echo language::translate('title_visitor', 'Visitor'); ?></th>
            <th><?php echo language::translate('title_country', 'Country'); ?></th>
            <th><?php echo language::translate('title_pageviews', 'Pageviews'); ?></th>
            <th><?php echo language::translate('title_referrer', 'Referrer'); ?></th>
            <th class="text-center"><?php echo language::translate('title_time', 'Time'); ?></th>
          </tr>
        </thead>
        <tbody>
<?php
  $visitors_query = database::query(
    "select * from ". DB_TABLE_VISITORS ."
    where date_updated > '". date('Y-m-d H:i:s', strtotime('-15 minutes')) ."'
    order by date_updated desc;"
  );

  while ($visitor = database::fetch($visitors_query)) {
?>
          <tr>
            <td><?php echo (strtotime($visitor['date_updated']) > strtotime('-5 minutes')) ? functions::draw_fonticon('fa-circle', 'style="color: #99cc66;"') : functions::draw_fonticon('fa-clock-o', 'style="color: #999;"'); ?></td>
            <td><a target="_blank" href="https://geoiptool.com/?ip=<?php echo urlencode($visitor['ip']); ?>" title="<?php echo htmlspecialchars($visitor['user_agent']); ?>"><?php echo $visitor['host']; ?></a><br />
              <!--<small style="font-size: 0.8em;"><a target="_blank" href="<?php echo htmlspecialchars($visitor['last_page']); ?>" title="<?php echo htmlspecialchars($visitor['last_page']); ?>"><?php echo htmlspecialchars(parse_url($visitor['last_page'], PHP_URL_PATH)); ?></small>-->
              <small style="font-size: 0.8em;"><a target="_blank" href="<?php echo htmlspecialchars($visitor['last_page']); ?>"><?php echo htmlspecialchars($visitor['last_page']); ?></small>
            </td>
            <td class="text-center"><?php echo $visitor['country']; ?></td>
            <td class="text-center"><?php echo $visitor['pageviews']; ?></td>
            <td>
              <?php if (!empty($visitor['referrer'])) { ?>
              <a target="_blank" href="<?php echo htmlspecialchars($visitor['referrer']); ?>" title="<?php echo htmlspecialchars($visitor['referrer']); ?>"><?php echo htmlspecialchars(parse_url($visitor['referrer'], PHP_URL_HOST)); ?></a>
              <?php } else { ?>
              <em><?php echo language::translate('title_direct', 'Direct'); ?></em>
              <?php } ?>
            </td>
            <td class="text-right"><?php echo strftime(language::$selected['format_datetime'], strtotime($visitor['date_updated'])); ?></td>
          </tr>
<?php
  }
?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<script>
<?php
  $visitors_query = database::query(
    "select count(id) as total_visits, sum(pageviews) as total_pageviews, date_created, date_format(date_created, '%Y-%m-%d') as date from ". DB_TABLE_VISITORS ."
    where date_created >= '". date('Y-m-d H:i:s', strtotime('-30 days')) ."'
    group by date
    order by date_created asc;"
  );

  $visitors = array();
  while ($day = database::fetch($visitors_query)) {
    $visitors[$day['date']] = array(
      'total_visits' => $day['total_visits'],
      'total_pageviews' => $day['total_pageviews'],
    );
  }

  for ($timestamp=time(); strtotime('-30 days') < $timestamp; $timestamp = strtotime('-1 day', $timestamp)) {
    $visitors[date('Y-m-d', $timestamp)]['label'] = date('j', $timestamp);
    if (!isset($visitors[date('Y-m-d', $timestamp)]['total_visits'])) $visitors[date('Y-m-d', $timestamp)]['total_visits'] = 0;
    if (!isset($visitors[date('Y-m-d', $timestamp)]['total_pageviews'])) $visitors[date('Y-m-d', $timestamp)]['total_pageviews'] = 0;
  }

  $visitors[date('Y-m-d')]['label'] = '★'.$visitors[date('Y-m-d')]['label'];

  ksort($visitors);
?>
  var data = {
    labels: <?php echo json_encode(array_column($visitors, 'label')); ?>,
    series: <?php echo json_encode(array(array_column($visitors, 'total_visits')/*,array_column($visitors, 'total_pageviews')*/)); ?>
  };

  var options = {
    showArea: true,
    lineSmooth: true
  };

  var responsiveOptions = [
    ['screen and (max-width: 640px)', {
      seriesBarDistance: 5,
      axisX: {
        labelInterpolationFnc: function (value) {
          return value[0];
        }
      }
    }]
  ];

  new Chartist.Line('#chart-visits', data, options, responsiveOptions);
</script>