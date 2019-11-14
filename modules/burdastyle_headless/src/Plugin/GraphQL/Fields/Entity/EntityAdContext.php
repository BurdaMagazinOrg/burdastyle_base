<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\Entity;

use Drupal;
use Drupal\ad_entity\TargetingCollection;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "entity_ad_context",
 *   name = "entityAdContext",
 *   type = "string",
 *   description = @Translation("Loads ad context for the entity."),
 *   parents = {"Entity"}
 * )
 */
class EntityAdContext extends FieldPluginBase implements ContainerFactoryPluginInterface {

  use DependencySerializationTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition
    );
  }

  /**
   *
   * Filter the main channel out of the targeting.
   *
   * @param \Drupal\ad_entity\TargetingCollection $targeting_collection
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function filterMainChannel(&$targeting_collection, $entity) {
    if (!$targeting_collection->isEmpty()) {

      $targeting_channel = $targeting_collection->get('channel');
      if(isset($targeting_channel) && is_array($targeting_channel) && $entity->hasField('field_channel')) {
        $channel_term = $entity->get('field_channel')->referencedEntities()[0];
        if ($channel_term) {
          $ad_context_item = $channel_term->get('field_ad_context')->first();
          if(isset($ad_context_item)) {
            $ad_context = $ad_context_item->getValue();
            $channel_term_channel = NestedArray::getValue($ad_context, ['context', 'context_settings', 'targeting', 'targeting', 'channel']);
            if(isset($channel_term_channel)) {
              $targeting_collection->set('channel', $channel_term_channel);
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof ContentEntityInterface) {
      /** @var \Drupal\ad_entity\Plugin\AdContextManager $ad_context_service */
      $ad_context_service = Drupal::service('ad_entity.context_manager');

      $ad_context_service->collectContextDataFrom($value, 'default');
      if (!empty($ad_context_service->getContextDataForPlugin('turnoff'))) {
        return NULL;
      }

      $targeting_collection = new TargetingCollection();

      $targeting_context_data = $ad_context_service->getContextDataForPlugin('targeting');
      foreach ($targeting_context_data as $targeting_context_entry) {
        if (isset($targeting_context_entry['settings']['targeting'])) {
          $targeting_collection->collectFromCollection(new TargetingCollection($targeting_context_entry['settings']['targeting']));
        }
      }

      try {
        $this->filterMainChannel($targeting_collection, $value);
      } catch (Drupal\Core\Entity\EntityStorageException $e) {
        return null;
      }

      yield $targeting_collection->toJson();
    }
  }

}
