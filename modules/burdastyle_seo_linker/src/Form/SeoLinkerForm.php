<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\Form\SeoLinkerForm.
 */

namespace Drupal\burdastyle_seo_linker\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for SEO Linker edit forms.
 *
 * @ingroup burdastyle_seo_linker
 */
class SeoLinkerForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\burdastyle_seo_linker\Entity\SeoLinker */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label SEO Linker.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label SEO Linker.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.seo_linker.canonical', ['seo_linker' => $entity->id()]);
  }

}
