<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\Entity\SeoLinker.
 */

namespace Drupal\burdastyle_seo_linker\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for SEO Linker entities.
 */
class SeoLinkerViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['seo_linker']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('SEO Linker'),
      'help' => $this->t('The SEO Linker ID.'),
    );

    return $data;
  }

}
