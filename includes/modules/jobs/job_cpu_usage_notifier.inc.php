<?php

  class job_cpu_usage_notifier {
    public $id = __CLASS__;
    public $name = 'CPU Usage Notifier';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';
    public $priority = 0;

    public function process($force, $last_run) {

      if (empty($force)) {
        if (empty($this->settings['status'])) return;

        if (!empty($this->settings['working_hours'])) {
          list($from_time, $to_time) = explode('-', $this->settings['working_hours']);
          if (time() < strtotime("Today $from_time") || time() > strtotime("Today $to_time")) return;
        }

        switch ($this->settings['frequency']) {
          case 'Hourly':
            if (date('Ymdh', strtotime($last_run)) == date('Ymdh')) return;
            break;
          case 'Daily':
            if (date('Ymd', strtotime($last_run)) == date('Ymd')) return;
            break;
        }
      }

      echo 'Checking averge CPU usage...' . PHP_EOL;

      if (!$cpu_usage = sys_getloadavg()) {
        echo '[Error]' . PHP_EOL;
        return;
      }

      echo '1 min: ' . $cpu_usage[0] .'%' . PHP_EOL;
      echo '5 min: ' . $cpu_usage[1] .'%' . PHP_EOL;
      echo '15 min: ' . $cpu_usage[2] .'%' . PHP_EOL;

      if ($cpu_usage[2] >= $this->settings['limit']) {

        if (empty($this->settings['email_recipient'])) {
          $this->settings['email_recipient'] = settings::get('store_email');
        }

        echo 'Sending report to '. $this->settings['email_recipient'];

        $email = new ent_email();
        $email->add_recipient($this->settings['email_recipient'])
              ->set_subject('[Error Report] '. settings::get('store_name'))
              ->add_body(PLATFORM_NAME .' '. PLATFORM_VERSION ."\r\n\r\n". 'CPU usage for the last 15 minutes is '. $cpu_usage[2] .'%.')
              ->send();
      }
    }

    function settings() {

      return [
        [
          'key' => 'status',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_status', 'Status'),
          'description' => language::translate(__CLASS__.':description_status', 'Enables or disables the module.'),
          'function' => 'toggle("e/d")',
        ],
        [
          'key' => 'frequency',
          'default_value' => 'Hourly',
          'title' => language::translate(__CLASS__.':title_frequency', 'Frequency'),
          'description' => language::translate(__CLASS__.':description_frequency', 'How often the task should be processed.'),
          'function' => 'radio("Hourly","Daily")',
        ],
        [
          'key' => 'email_recipient',
          'default_value' => settings::get('store_email'),
          'title' => language::translate(__CLASS__.':title_email_recipient', 'Email Recipient'),
          'description' => language::translate(__CLASS__.':description_email_recipient', 'The email address where reports will be sent.'),
          'function' => 'text()',
        ],
        [
          'key' => 'limit',
          'default_value' => '15',
          'title' => language::translate(__CLASS__.':title_limit', 'Limit'),
          'description' => language::translate(__CLASS__.':description_limit', 'The CPU usage limit that should trigger a notification.'),
          'function' => 'number()',
        ],
        [
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ],
      ];
    }

    public function install() {
    }

    public function uninstall() {
    }
  }
