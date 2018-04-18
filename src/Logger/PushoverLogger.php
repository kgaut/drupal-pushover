<?php

namespace Drupal\pushover\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Psr\Log\LoggerInterface;

class PushoverLogger implements LoggerInterface {

  use RfcLoggerTrait;

  public function log($level, $message, array $context = array()) {
    if(\Drupal::config('pushover.notifications')->get('watchdog')) {
      dd('asa');
      if (\in_array($level, [RfcLogLevel::EMERGENCY, RfcLogLevel::ALERT, RfcLogLevel::CRITICAL, RfcLogLevel::ERROR])) {
        $level_labels = RfcLogLevel::getLevels();
        $title = t('Error @level on @sitename', [
          '@level' => $level_labels[$level],
          '@sitename' => \Drupal::config('system.site')->get('name'),
        ]);
        $message = strip_tags(t($message, $context)->render());
        \Drupal::service('pushover.sender')->sendNotification($title, $message, NULL, NULL, 'alien');
      }
    }
  }

}