<?php

namespace Drupal\pushover\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PushoverAdminConfiguration.
 */
class PushoverAdminConfiguration extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'pushover.config',
      'pushover.notifications',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pushover_config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pushover.config');
    $notifications = $this->config('pushover.notifications');
    $form['basic'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic settings'),
    ];

    $form['basic']['explanations'] = [
      '#markup' => '<p>' . $this->t('First, you need to create an app on pushover : <a target="_blank" href="@url">@url</a>', ['@url' => 'https://pushover.net/apps/build'])
    ];

    $form['basic']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t("Your application key, specific for the app you've just created"),
      '#maxlength' => 128,
      '#size' => 64,
      '#required' => TRUE,
      '#default_value' => $config->get('api_key'),
    ];
    $form['basic']['user_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Key'),
      '#description' => $this->t('Your user key, global for all your apps, you can find it on <a target="_blank" href="@url">@url</a>', ['@url' => 'https://pushover.net/']),
      '#maxlength' => 128,
      '#size' => 64,
      '#required' => TRUE,
      '#default_value' => $config->get('user_key'),
    ];
    $form['basic']['devices'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Devices'),
      '#description' => $this->t('Commas separated list of the devices you want to target, leave empty to target all your devices'),
      '#maxlength' => 128,
      '#size' => 64,
      '#default_value' => $config->get('devices'),
    ];
    if ($config->get('api_key') != '' && $sounds = \Drupal::service('pushover.sender')->getSoundOptions()) {
      $form['basic']['sound'] = [
        '#type' => 'select',
        '#title' => $this->t('Sound'),
        '#options' => $sounds,
        '#default_value' => $config->get('sound'),
        '#description' => $this->t('The sound to use for the notification'),
      ];
    }
    else {
      $form['basic']['sound'] = [
        '#type' => 'value',
        '#value' => 'pushover',
      ];
      $form['basic']['sound_help'] = [
        '#markup' => '<p>' . t('Save API key to choose a sound.') . '</p>'
      ];
    }

    $form['notifications'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Notifications'),
    ];

    $form['notifications']['notification_watchdog'] = [
      '#type' => 'checkbox',
      '#title' => 'Notification when error are created on watchdog',
      '#default_value' => $notifications->get('watchdog'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save settings'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('pushover.config')
      ->set('user_key', $form_state->getValue('user_key'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('devices', trim($form_state->getValue('devices')))
      ->set('sound', trim($form_state->getValue('sound')))
      ->save();
    $this->config('pushover.notifications')
      ->set('watchdog', $form_state->getValue('notification_watchdog'))
      ->save();

    drupal_set_message($this->t('The configuration options have been saved.'));
    drupal_set_message($this->t('A test notification has been sent.'));

    \Drupal::service('pushover.sender')->sendNotification($this->t('Test config'), $this->t('Hello world !'));
  }

}
