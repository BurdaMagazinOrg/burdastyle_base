<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLType(
 *   id = "ad_entity_config",
 *   name = "AdEntityConfig",
 * )
 */
class AdEntityConfig extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info = NULL) {
    /*if (isset($object['#tag']) && $object['#tag'] === 'meta') {
      return !array_key_exists('property', $object['#attributes']);
    }

    return FALSE;*/
    // TODO: define when applicable
    return TRUE;
  }

}
