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
    $form['explanations'] = [
      '#markup' => '<p>' . $this->t('First, you need to create an app on pushover : <a target="_blank" href="@url">@url</a>', ['@url' => 'https://pushover.net/apps/build'])
    ];
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t("Your application key, specific for the app you've just created"),
      '#maxlength' => 128,
      '#size' => 64,
    ];
    $form['user_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User Key'),
      '#description' => $this->t('Your user key, global for all your apps, you can find it on <a target="_blank" href="@url">@url</a>', ['@url' => 'https://pushover.net/']),
      '#maxlength' => 128,
      '#size' => 64,
    ];
    $form['devices'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Devices'),
      '#description' => $this->t('Commas separated list of the devices you want to target, leave empty to target all your devices'),
      '#maxlength' => 128,
      '#size' => 64,
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
    parent::submitForm($form, $form_state);

    $this->config('pushover.config')
      ->set('user_key', $form_state->getValue('user_key'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('devices', trim($form_state->getValue('devices')))
      ->save();

  }

}
