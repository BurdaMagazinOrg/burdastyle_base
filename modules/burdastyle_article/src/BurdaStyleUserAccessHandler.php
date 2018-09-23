<?php

namespace Drupal\burdastyle_article;

use Drupal\user\UserAccessControlHandler;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultNeutral;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the custom access control handler for the user entity type.
 *
 * Only difference to core is that the check for an active user entity is
 * removed when being viewed.
 */
class BurdaStyleUserAccessHandler extends UserAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\user\UserInterface $entity*/

    // We don't treat the user label as privileged information, so this check
    // has to be the first one in order to allow labels for all users to be
    // viewed, including the special anonymous user.
    if ($operation === 'view label') {
      return AccessResult::allowed();
    }

    // The anonymous user's profile can neither be viewed, updated nor deleted.
    if ($entity->isAnonymous()) {
      return AccessResult::forbidden();
    }

    // Administrators can view/update/delete all user profiles.
    if ($account->hasPermission('administer users')) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    switch ($operation) {
      case 'view':
        // Allow view access regardless of whether the account is active.
        if ($account->hasPermission('access user profiles')) {
          return AccessResult::allowed()->cachePerPermissions()->addCacheableDependency($entity);
        }
        // Users can view own profiles at all times.
        elseif ($account->id() == $entity->id()) {
          return AccessResult::allowed()->cachePerUser();
        }
        else {
          return AccessResultNeutral::neutral("The 'access user profiles' permission is required.")->cachePerPermissions()->addCacheableDependency($entity);
        }
        break;

      case 'update':
        // Users can always edit their own account.
        return AccessResult::allowedIf($account->id() == $entity->id())->cachePerUser();

      case 'delete':
        // Users with 'cancel account' permission can cancel their own account.
        return AccessResult::allowedIf($account->id() == $entity->id() && $account->hasPermission('cancel account'))->cachePerPermissions()->cachePerUser();
    }

    // No opinion.
    return AccessResult::neutral();
  }

}
