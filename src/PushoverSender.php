<?php
namespace Drupal\pushover;

use GuzzleHttp\Exception\RequestException;

class PushoverSender {

  public static $url = 'https://api.pushover.net/1/messages.json';
  public $options = [];

  public function __construct() {
    $config = \Drupal::config('pushover.config')->getRawData();
    $this->options = [
      'method' => 'POST',
      'data' => [
        'token' => $config['api_key'],
        'user' => $config['user_key'],
        'message' => '',
        'title' => '',
      ],
    ];
    if (trim($config['devices']) !== '') {
      $this->options['data']['device'] = $config['devices'];
    }
  }

  public function sendNotification($title, $message, $url = NULL, $url_title = NULL) {
    $this->options['data']['title'] = (string) $title;
    $this->options['data']['message'] = (string) $message;
    if ($url) {
      $this->options['data']['url'] = $url;
    }
    if ($url_title) {
      $this->options['data']['url_title'] = (string) $url_title;
    }
    $this->send();
  }

  private function send() {
    $client = \Drupal::httpClient();
    $url = self::$url;
    $options['form_params'] = $this->options['data'];
    try {
      $response = $client->request('POST', $url, $options);
    }
    catch (RequestException $e) {
      watchdog_exception('pushover', $e);
    }
  }
}