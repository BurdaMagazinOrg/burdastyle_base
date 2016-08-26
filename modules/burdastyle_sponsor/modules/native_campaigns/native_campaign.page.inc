<?php

/**
 * @file
 * Contains burdastyle_sponsor.page.inc.
 *
 * Page callback for Native campaign entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Native campaign templates.
 *
 * Default template: native_campaign.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_native_campaign(array &$variables) {
  // Fetch NativeCampaign Entity Object.
  $native_campaign = $variables['elements']['#native_campaign'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
