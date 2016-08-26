<?php

namespace Drupal\native_campaigns\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Native campaign entity.
 *
 * @ingroup native_campaigns
 *
 * @ContentEntityType(
 *   id = "native_campaign",
 *   label = @Translation("Native campaign"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\native_campaigns\NativeCampaignListBuilder",
 *     "views_data" = "Drupal\native_campaigns\Entity\NativeCampaignViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\native_campaigns\Form\NativeCampaignForm",
 *       "add" = "Drupal\native_campaigns\Form\NativeCampaignForm",
 *       "edit" = "Drupal\native_campaigns\Form\NativeCampaignForm",
 *       "delete" = "Drupal\native_campaigns\Form\NativeCampaignDeleteForm",
 *     },
 *     "access" = "Drupal\native_campaigns\NativeCampaignAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\native_campaigns\NativeCampaignHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "native_campaign",
 *   admin_permission = "administer native campaign entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/native_campaign/{native_campaign}",
 *     "add-form" = "/admin/structure/native_campaign/add",
 *     "edit-form" = "/admin/structure/native_campaign/{native_campaign}/edit",
 *     "delete-form" = "/admin/structure/native_campaign/{native_campaign}/delete",
 *     "collection" = "/admin/structure/native_campaign",
 *   },
 *   field_ui_base_route = "native_campaign.settings"
 * )
 */
class NativeCampaign extends ContentEntityBase implements NativeCampaignInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['partner'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Partner'))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['logo'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Logo'))
      ->setDisplayOptions('form', array(
        'type' => 'inline_entity_form_complex',
        'weight' => -3,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'allow_new' => 1,
          'allow_existing' => 1,
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('target_type', 'media')
      ->setSetting('handler', 'default:media')
      ->setSetting('handler_settings', array(
        'target_bundles' => array(
          'image' => 'image',
        ),
      ));

    $fields['tracking_pixel'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Tracking Pixel'))
      ->setDisplayOptions('form', array(
        'type' => 'text_textarea',
        'weight' => -1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Published'))
      ->setDefaultValue(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Native campaign entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
