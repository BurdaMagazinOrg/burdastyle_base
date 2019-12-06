<?php

namespace Drupal\burdastyle_headless\Plugin\Purge\Queuer;

use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuerBase;

/**
 * Queues URLs to be purged.
 *
 * @PurgeQueuer(
 *   id = "cloudflare_queuer",
 *   label = @Translation("Cloudflare queuer"),
 *   description = @Translation("Queues URLs to be purged."),
 *   enable_by_default = true,
 * )
 */
class CloudflareQueuerPlugin extends QueuerBase implements QueuerInterface {

}
