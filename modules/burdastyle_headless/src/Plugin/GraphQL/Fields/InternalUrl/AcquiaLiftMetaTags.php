<?php

namespace Drupal\burdastyle_headless\Plugin\GraphQL\Fields\InternalUrl;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\graphql\GraphQL\Buffers\SubRequestBuffer;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @GraphQLField(
 *   secure = true,
 *   id = "url_acquia_lift_metatags",
 *   name = "acquia_lift_metatags",
 *   type = "[Metatag]",
 *   description = @Translation("Loads acquia_lift metatags for the URL."),
 *   parents = {"InternalUrl", "EntityCanonicalUrl"}
 * )
 */
class AcquiaLiftMetaTags extends FieldPluginBase implements ContainerFactoryPluginInterface {
  use DependencySerializationTrait;

  /**
   * The sub-request buffer service.
   *
   * @var \Drupal\graphql\GraphQL\Buffers\SubRequestBuffer
   */
  protected $subRequestBuffer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('graphql.buffer.subrequest')
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
   * @param \Drupal\graphql\GraphQL\Buffers\SubRequestBuffer $subRequestBuffer
   *   The sub-request buffer service.
   */
  public function __construct(
    array $configuration,
    $pluginId,
    $pluginDefinition,
    SubRequestBuffer $subRequestBuffer
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->subRequestBuffer = $subRequestBuffer;
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if ($value instanceof Url) {
      $resolve = $this->subRequestBuffer->add($value, function () {
        /** @var \Drupal\acquia_lift\Service\Context\PageContext $page_context */
        $page_context = \Drupal::service('acquia_lift.service.context.page_context');

        $fakePage['#attached']['html_head'] = [];
        $page_context->populate($fakePage);

        $tags = array_map(function ($tag) {
          return $tag[0];
        }, $fakePage['#attached']['html_head']);

        $tags = array_filter($tags, function ($tag) {
          return isset($tag['#tag']) && $tag['#tag'] === 'meta';
        });

        return $tags;
      });

      return function ($value, array $args, ResolveContext $context, ResolveInfo $info) use ($resolve) {
        $tags = $resolve();
        foreach ($tags->getValue() as $tag) {
          yield $tag;
        }
      };
    }
  }

}
