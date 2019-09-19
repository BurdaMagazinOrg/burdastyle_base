<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdContext;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_context_key",
 *   name = "key",
 *   type = "String",
 *   parents = {"AdContext", "AdEntityConfig"}
 * )
 */
class Key extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (is_string($value['key'])) {
      yield $value['key'];
    }
  }

}
