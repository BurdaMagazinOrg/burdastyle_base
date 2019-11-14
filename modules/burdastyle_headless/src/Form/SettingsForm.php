<?php

namespace Drupal\burdastyle_headless\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The settings form for BurdaStyle headless module.
 *
 * @package Drupal\burdastyle_headless\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'burdastyle_headless.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('burdastyle_headless.settings');

    $form['frontend_base_url'] = [
      '#type' => 'url',
      '#title' => $this->t('The Base URL for frontend server'),
      '#default_value' => $config->get('frontend_base_url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('burdastyle_headless.settings')
      ->set('frontend_base_url', $form_state->getValue('frontend_base_url'))
      ->save();
  }

}
