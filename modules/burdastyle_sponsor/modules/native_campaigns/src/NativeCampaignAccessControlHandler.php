<?php

namespace Drupal\native_campaigns;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Native campaign entity.
 *
 * @see \Drupal\native_campaigns\Entity\NativeCampaign.
 */
class NativeCampaignAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\native_campaigns\Entity\NativeCampaignInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished native campaign entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published native campaign entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit native campaign entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete native campaign entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add native campaign entities');
  }

}
