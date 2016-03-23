<?php

/**
 * @file
 * Contains \Drupal\burdastyle_seo_linker\SeoLinkerInterface.
 */

namespace Drupal\burdastyle_seo_linker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining SEO Linker entities.
 *
 * @ingroup burdastyle_seo_linker
 */
interface SeoLinkerInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the SEO Linker type.
   *
   * @return string
   *   The SEO Linker type.
   */
  public function getType();

  /**
   * Gets the SEO Linker name.
   *
   * @return string
   *   Name of the SEO Linker.
   */
  public function getName();

  /**
   * Sets the SEO Linker name.
   *
   * @param string $name
   *   The SEO Linker name.
   *
   * @return \Drupal\burdastyle_seo_linker\SeoLinkerInterface
   *   The called SEO Linker entity.
   */
  public function setName($name);

  /**
   * Gets the SEO Linker creation timestamp.
   *
   * @return int
   *   Creation timestamp of the SEO Linker.
   */
  public function getCreatedTime();

  /**
   * Sets the SEO Linker creation timestamp.
   *
   * @param int $timestamp
   *   The SEO Linker creation timestamp.
   *
   * @return \Drupal\burdastyle_seo_linker\SeoLinkerInterface
   *   The called SEO Linker entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the SEO Linker published status indicator.
   *
   * Unpublished SEO Linker are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the SEO Linker is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a SEO Linker.
   *
   * @param bool $published
   *   TRUE to set this SEO Linker to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\burdastyle_seo_linker\SeoLinkerInterface
   *   The called SEO Linker entity.
   */
  public function setPublished($published);

}
