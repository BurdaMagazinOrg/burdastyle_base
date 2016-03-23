<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\Form\SeoLinkerTypeForm.
 */

namespace Drupal\burdastyle_seo_linker\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SeoLinkerTypeForm.
 *
 * @package Drupal\burdastyle_seo_linker\Form
 */
class SeoLinkerTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $seo_linker_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $seo_linker_type->label(),
      '#description' => $this->t("Label for the SEO Linker type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $seo_linker_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\burdastyle_seo_linker\Entity\SeoLinkerType::load',
      ),
      '#disabled' => !$seo_linker_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $seo_linker_type = $this->entity;
    $status = $seo_linker_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label SEO Linker type.', [
          '%label' => $seo_linker_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label SEO Linker type.', [
          '%label' => $seo_linker_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($seo_linker_type->urlInfo('collection'));
  }

}
