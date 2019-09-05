<?php

namespace Drupal\burdastyle_headless\HttpKernel;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Path\AliasManagerInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * Processes the outbound path for BurdaStyle headless.
 *
 * This outbound path process will generate correct URLs in backend for viewing
 * of published/unpublished articles in frontend. That's important for editorial
 * workflow.
 *
 * Other purpose is for AMP pages, where URL will be overwritten to provide
 * correct frontend URLs.
 */
class FrontendPathProcessor implements OutboundPathProcessorInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The path alias manager.
   *
   * @var \Drupal\Core\Path\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * Constructs a FrontendPathProcessor object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Path\AliasManagerInterface $alias_manager
   *   The path alias manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AliasManagerInterface $alias_manager) {
    $this->configFactory = $config_factory;
    $this->aliasManager = $alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound($path, &$options = [], Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {

    // Early return if no 'frontend_base_url' is set yet.
    $frontend_base_url = $this->configFactory->get('burdastyle_headless.settings')->get('frontend_base_url');
    if (empty($frontend_base_url)) {
      return $path;
    }

    // Only act on valid internal paths and when a domain loads.
    if (empty($path) || !empty($options['external'])) {
      return $path;
    }

    // Do not change Base URL for GraphQL requests.
    if ($request && $request->attributes && $request->attributes->has('_graphql')) {
      return $path;
    }

    // Workaround for AMP pages.
    if ($request && $request->query && $request->query->has('amp')
        && empty($options['entity_type'])
        && isset($options['absolute']) && $options['absolute'] === TRUE
        && $request->getPathInfo() === $path
    ) {
      return $path;
    }

    // Get the current language.
    $langcode = NULL;
    if (!empty($options['language'])) {
      $langcode = $options['language']->getId();
    }
    // Get the URL object for this request.
    $alias = $this->aliasManager->getPathByAlias($path, $langcode);
    $url = Url::fromUserInput($alias, $options);

    if (!$url->isRouted()) {
      return $path;
    }

    $route_name_parts = explode('.', $url->getRouteName());
    if (end($route_name_parts) !== 'canonical') {
      return $path;
    }

    $options['base_url'] = trim($frontend_base_url, '/');
    $options['absolute'] = TRUE;

    return $path;
  }

}
