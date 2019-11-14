<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLType(
 *   id = "ad_entity_adtech",
 *   name = "AdEntityAdtech",
 *   interfaces = {"AdEntity"},
 * )
 */
class AdEntityAdtech extends TypePluginBase {

//  TODO: derive this and other plugins for adEntity

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info = NULL) {
    return $object->type_plugin_id === "adtech_factory";
  }

}
