<?php

namespace Drupal\native_campaigns\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Native campaign entities.
 *
 * @ingroup native_campaigns
 */
interface NativeCampaignInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Native campaign name.
   *
   * @return string
   *   Name of the Native campaign.
   */
  public function getName();

  /**
   * Sets the Native campaign name.
   *
   * @param string $name
   *   The Native campaign name.
   *
   * @return \Drupal\native_campaigns\Entity\NativeCampaignInterface
   *   The called Native campaign entity.
   */
  public function setName($name);

  /**
   * Gets the Native campaign creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Native campaign.
   */
  public function getCreatedTime();

  /**
   * Sets the Native campaign creation timestamp.
   *
   * @param int $timestamp
   *   The Native campaign creation timestamp.
   *
   * @return \Drupal\native_campaigns\Entity\NativeCampaignInterface
   *   The called Native campaign entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Native campaign published status indicator.
   *
   * Unpublished Native campaign are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Native campaign is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Native campaign.
   *
   * @param bool $published
   *   TRUE to set this Native campaign to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\native_campaigns\Entity\NativeCampaignInterface
   *   The called Native campaign entity.
   */
  public function setPublished($published);

}
