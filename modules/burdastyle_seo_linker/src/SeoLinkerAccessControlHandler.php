<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\SeoLinkerAccessControlHandler.
 */

namespace Drupal\burdastyle_seo_linker;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the SEO Linker entity.
 *
 * @see \Drupal\burdastyle_seo_linker\Entity\SeoLinker.
 */
class SeoLinkerAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\burdastyle_seo_linker\SeoLinkerInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished seo linker entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published seo linker entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit seo linker entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete seo linker entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add seo linker entities');
  }

}
