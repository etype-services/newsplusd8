<?php

namespace Drupal\etype_commerce\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the Gift Subscription entity.
 *
 * @ingroup gift_subscription
 *
 * @ContentEntityType(
 *   id = "gift_subscription",
 *   label = @Translation("gift_subscription"),
 *   base_table = "gift_subscriptions",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class GiftSubscription extends ContentEntityBase implements ContentEntityInterface {

  /**
   * Gift Subscription Class.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The Entity Type.
   *
   * @return array
   *   Return schema.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Goft Subscription entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Gift Subscription entity.'))
      ->setReadOnly(TRUE);

    $fields['order_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Order Id'))
      ->setDescription(t('The Order Id.'))
      ->setReadOnly(FALSE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Recipient Email Address'))
      ->setDescription(t('The email address of the recipient.'))
      ->setReadOnly(FALSE);

    $fields['print'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Print Subscription'))
      ->setDescription(t('Flag for print subscription'))
      ->setReadOnly(FALSE);

    $fields['duration'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subscription Duration'))
      ->setDescription(t('How long the subscription lasts'))
      ->setReadOnly(FALSE);

    $fields['publication'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Publication'))
      ->setDescription(t('Which publication the subscription is for'))
      ->setReadOnly(FALSE);

    $fields['paid'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Paid'))
      ->setDescription(t('Paid flag'))
      ->setReadOnly(FALSE);

    return $fields;
  }

}
