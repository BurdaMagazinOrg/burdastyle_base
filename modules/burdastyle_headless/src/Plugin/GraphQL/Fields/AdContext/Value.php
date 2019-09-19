<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdContext;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_context_value",
 *   name = "value",
 *   description="The value as json string.",
 *   type = "String",
 *   parents = {"AdContext", "AdEntityConfig"}
 * )
 */
class Value extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (is_array($value) || is_string($value)) {
      yield json_encode($value['value']);
    }
  }

}
