<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\SeoLinkerListBuilder.
 */

namespace Drupal\burdastyle_seo_linker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of SEO Linker entities.
 *
 * @ingroup burdastyle_seo_linker
 */
class SeoLinkerListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('SEO Linker ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\burdastyle_seo_linker\Entity\SeoLinker */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.seo_linker.edit_form', array(
          'seo_linker' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
