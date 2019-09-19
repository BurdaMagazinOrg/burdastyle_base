<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdEntity;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_entity_config",
 *   name = "config",
 *   type = "[AdEntityConfig]",
 *   parents = {"AdEntity"}
 * )
 */
class AdEntityConfig extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    foreach ($value->getThirdPartySettings('ad_entity_adtech') as $key => $value) {
      if ($key !== 'targeting') {
        yield [
          'key' => $key,
          'value' => $value
        ];
      }
    }
  }

}
