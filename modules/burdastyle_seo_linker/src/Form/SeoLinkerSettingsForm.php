<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\Form\SeoLinkerSettingsForm.
 */

namespace Drupal\burdastyle_seo_linker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SeoLinkerSettingsForm.
 *
 * @package Drupal\burdastyle_seo_linker\Form
 *
 * @ingroup burdastyle_seo_linker
 */
class SeoLinkerSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'SeoLinker_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }


  /**
   * Defines the settings form for SEO Linker entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['SeoLinker_settings']['#markup'] = 'Settings form for SEO Linker entities. Manage field settings here.';
    return $form;
  }

}
