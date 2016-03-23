<?php

/**
 * @file
 * Contains seo_linker.page.inc..
 *
 * Page callback for SEO Linker entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for SEO Linker templates.
 *
 * Default template: seo_linker.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_linker(array &$variables) {
  // Fetch SeoLinker Entity Object.
  $seo_linker = $variables['elements']['#seo_linker'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: seo_linker-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of seo_linker-types.
*
* @see block_content_add_page()
*/
function template_preprocess_seo_linker_content_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.seo_linker.add_form', array(
        'seo_linker_type' => $type->id()
      ), array('query' => $query))),
      'description' => array(
      '#markup' => $type->label(),
      ),
      'title' => $type->label(),
      'localized_options' => array(
      'query' => $query,
      ),
    );
  }
}
