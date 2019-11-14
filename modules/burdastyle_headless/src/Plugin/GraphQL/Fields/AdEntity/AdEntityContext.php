<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdEntity;

use Drupal;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_entity_context",
 *   name = "context",
 *   type = "[AdContext]",
 *   arguments = {
 *     "entityId" = "String!",
 *     "entityType" = "String!"
 *   },
 *   parents = {"AdEntity"}
 * )
 */
class AdEntityContext extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    $entity_type_manager = \Drupal::entityTypeManager();
    try {
      $storage = $entity_type_manager->getStorage($args['entityType']);
    }
    catch (\Exception $e) {
//      TODO: better error handling
      return NULL;
    }

    $entity = $storage->load($args['entityId']);

    if ($entity instanceof ContentEntityInterface) {

      /** @var \Drupal\ad_entity\Plugin\AdContextManager $ad_context_service */
      $ad_context_service = Drupal::service('ad_entity.context_manager');

//    TODO: specify view_mode via args
      $ad_context_service->collectContextDataFrom($entity, 'default');

      /** @var \Drupal\ad_entity\TargetingCollection $data */
      $data = $value->getTargetingFromContextData();

      foreach ($data->toArray() as $key => $value) {
        yield [
          'key' => $key,
          'value' => $value
        ];
      }
    }
  }

}
