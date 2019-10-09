<?php

namespace Drupal\burdastyle_headless;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

/**
 * Class BurdastyleHeadlessServiceProvider.
 *
 * Override redirect_response_subscriber of Drupal Core.
 *
 * @package Drupal\burdastyle_headless
 */
class BurdastyleHeadlessServiceProvider extends ServiceProviderBase implements ServiceProviderInterface {

  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('redirect_response_subscriber');
    $definition->setClass('Drupal\burdastyle_headless\EventSubscriber\BurdaStyleHeadlessRedirectResponseSubscriber');
  }

}
