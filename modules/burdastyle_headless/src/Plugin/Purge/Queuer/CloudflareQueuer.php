<?php

namespace Drupal\burdastyle_headless\Plugin\Purge\Queuer;

/**
 * Class CloudflareQueuer
 *
 * @package Drupal\burdastyle_headless\Plugin\Purge\Queue
 */
class CloudflareQueuer {

  public $purgeInvalidationFactory;

  public $purgeQueue;

  public $queuer;

  public function initialize() {
    if (is_null($this->queuer)) {
      $this->queuer = \Drupal::service('purge.queuers')->get('cloudflare_queuer');
      if ($this->queuer !== FALSE) {
        $this->purgeInvalidationFactory = \Drupal::service('purge.invalidation.factory');
        $this->purgeQueue = \Drupal::service('purge.queue');
      }
    }
    return $this->queuer !== FALSE;
  }

}
