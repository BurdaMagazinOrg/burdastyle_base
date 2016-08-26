<?php

namespace Drupal\native_campaigns\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Native campaign entities.
 */
class NativeCampaignViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['native_campaign']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Native campaign'),
      'help' => $this->t('The Native campaign ID.'),
    );

    return $data;
  }

}
