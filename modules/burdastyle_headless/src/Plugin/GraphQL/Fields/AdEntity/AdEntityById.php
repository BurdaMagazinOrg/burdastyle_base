<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\AdEntity;

use Drupal;
use Drupal\ad_entity\Entity\AdEntity;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "ad_entity_by_id",
 *   name = "adEntityById",
 *   type = "AdEntity",
 *   arguments = {
 *     "id" = "String!"
 *   },
 *   description = @Translation("Loads ad entity by id."),
 * )
 */
class AdEntityById extends FieldPluginBase implements ContainerFactoryPluginInterface {
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
   * Metatags constructor.
   *
   * @param array $configuration
   *   The plugin configuration array.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition array.
   * @param \Drupal\metatag\MetatagManagerInterface $metatagManager
   */
  public function __construct(
    array $configuration,
    $pluginId,
    $pluginDefinition
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    /** @var \Drupal\ad_entity\Entity\AdEntity $ad_entity */
    $ad_entity = AdEntity::load($args['id']);

    if (!$ad_entity) {
      // TODO: Should yield a cacheable value
      return yield NULL;
    }

    yield $ad_entity;
  }

}
