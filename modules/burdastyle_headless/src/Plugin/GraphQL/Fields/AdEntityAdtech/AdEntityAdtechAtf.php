<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdEntityAdtech;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_entity_adtech_atf",
 *   name = "atf",
 *   type = "String",
 *   parents = {"AdEntityAdtech"}
 * )
 */
class AdEntityAdtechAtf extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value->getThirdPartySetting('ad_entity_adtech', 'data_atf');
  }

}
