<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\Entity\SeoLinkerType.
 */

namespace Drupal\burdastyle_seo_linker\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\burdastyle_seo_linker\SeoLinkerTypeInterface;

/**
 * Defines the SEO Linker type entity.
 *
 * @ConfigEntityType(
 *   id = "seo_linker_type",
 *   label = @Translation("SEO Linker type"),
 *   handlers = {
 *     "list_builder" = "Drupal\burdastyle_seo_linker\SeoLinkerTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\burdastyle_seo_linker\Form\SeoLinkerTypeForm",
 *       "edit" = "Drupal\burdastyle_seo_linker\Form\SeoLinkerTypeForm",
 *       "delete" = "Drupal\burdastyle_seo_linker\Form\SeoLinkerTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\burdastyle_seo_linker\SeoLinkerTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seo_linker_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "seo_linker",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/seo_linker_type/{seo_linker_type}",
 *     "add-form" = "/admin/structure/seo_linker_type/add",
 *     "edit-form" = "/admin/structure/seo_linker_type/{seo_linker_type}/edit",
 *     "delete-form" = "/admin/structure/seo_linker_type/{seo_linker_type}/delete",
 *     "collection" = "/admin/structure/seo_linker_type"
 *   }
 * )
 */
class SeoLinkerType extends ConfigEntityBundleBase implements SeoLinkerTypeInterface {
  /**
   * The SEO Linker type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The SEO Linker type label.
   *
   * @var string
   */
  protected $label;

}
