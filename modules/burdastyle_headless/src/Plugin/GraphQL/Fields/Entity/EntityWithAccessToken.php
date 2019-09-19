<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\Entity;

use Drupal;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "entity_with_access_token",
 *   name = "entityWithAccessToken",
 *   type = "Entity",
 *   arguments = {
 *     "path" = "String!",
 *     "accessToken" = "String!",
 *   },
 *   description = @Translation("Loads entity by path and with access token."),
 * )
 */
class EntityWithAccessToken extends FieldPluginBase implements ContainerFactoryPluginInterface {

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
   * AdEntityById constructor.
   *
   * @param array $configuration
   *   The plugin configuration array.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition array.
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
    $path = $args['path'];
    $accessToken = $args['accessToken'];

    $url = Url::fromUserInput($path);

    // Only routed URLs can have access tokens.
    if (!$url->isRouted()) {
      return yield NULL;
    }

    // Get prefix and entity type from route name
    // E.g. entity.node.canonical => prefix: entity, type: node
    $route_parts = explode('.', $url->getRouteName());
    list($route_prefix, $route_entity_type) = $route_parts;

    $parameters = $url->getRouteParameters();

    if (($route_prefix === 'entity') && array_key_exists($route_entity_type, $parameters)) {
      $entity_id = $parameters[$route_entity_type];
      try {
        $entity = \Drupal::entityTypeManager()
          ->getStorage($route_entity_type)
          ->load($entity_id);
        $storage = \Drupal::entityTypeManager()->getStorage('access_token');
      } catch (\Exception $e) {
        return yield NULL;
      }

      $permission = 'access_unpublished ' . $entity->getEntityTypeId() . ($entity->getEntityType()->hasKey('bundle') ? ' ' . $entity->bundle() : '');
      $user_has_permission = \Drupal::currentUser()->getAccount()->hasPermission($permission);

      if ($user_has_permission) {
        $tokens = $storage->getQuery()
          ->condition('entity_type', $entity->getEntityType()->id())
          ->condition('entity_id', $entity->id())
          ->condition('value', $accessToken)
          ->execute();

        if ($tokens) {
          /** @var \Drupal\access_unpublished\AccessTokenInterface $token */
          $token = $storage->load(current($tokens));

          if (!$token->isExpired()) {
            return yield $entity->addCacheContexts(['user.permissions'])
              ->addCacheableDependency($token)
              ->mergeCacheMaxAge($token->get('expire')->value - \Drupal::time()
                  ->getRequestTime());
          }
        }
      }
    }

    yield NULL;
  }

}
