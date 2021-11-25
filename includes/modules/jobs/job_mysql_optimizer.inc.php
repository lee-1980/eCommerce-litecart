<?php

  class job_mysql_optimizer {
    public $id = __CLASS__;
    public $name = 'MySQL Optimizer';
    public $description = 'Defragmentize your MySQL database';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';
    public $priority = 0;

    public function __construct() {
    }

    public function process($force, $last_run) {

      if (empty($force)) {
        if (empty($this->settings['status'])) return;

        switch ($this->settings['run_frequency']) {
          case 'Daily':
            if (date('Ymd', strtotime($last_run)) == date('Ymd')) return;
            break;
          case 'Weekly':
            if (date('W', strtotime($last_run)) == date('W')) return;
            break;
          case 'Monthly':
            if (date('Ym', strtotime($last_run)) == date('Ym')) return;
            break;
        }
      }

      echo 'Optimizing MySQL Tables...' . PHP_EOL;

      $query = database::query(
        "select concat('`', table_schema, '`.`', table_name, '`') as `table` from `information_schema`.`tables`
        where engine = 'MyISAM'
        and table_schema = '". DB_DATABASE ."'
        and table_name like '". DB_TABLE_PREFIX ."%';"
      );

      while ($row = database::fetch($query)) {
        echo '  - ' . $row['table'] . PHP_EOL;
        database::query("optimize table ". $row['table'] .";");
      }
    }

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
          'key' => 'run_frequency',
          'default_value' => 'Monthly',
          'title' => language::translate(__CLASS__.':title_run_frequency', 'Run Frequency'),
          'description' => language::translate(__CLASS__.':description_run_frequency', 'How often the job should be executed.'),
          'function' => 'radio("Daily","Weekly","Monthly")',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module in the given priority order.'),
          'function' => 'number()',
        ),
      );
    }

    public function install() {
    }

    public function uninstall() {
    }
  }
