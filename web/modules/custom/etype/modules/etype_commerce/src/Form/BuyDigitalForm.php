<?php

namespace Drupal\etype_commerce\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BuyDigitalForm provides form to buy Digital subscription.
 *
 * @package Drupal\etype_commerce\Form
 */
class BuyDigitalForm extends FormBase {

  /**
   * Settings holder.
   *
   * @var Config
   */
  protected $config;

  /**
   * BuyDigitalForm constructor.
   */
  public function __construct() {
    $this->config = $config = \Drupal::config('etype_commerce.adminsettings');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'commerce_order_item_add_to_cart_form_commerce_product_5';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $orderId = NULL): array {

    $form['purchased_entity[0][attributes][attribute_subscription_duration]'] = [
      '#type' => 'hidden',
      '#default_value' => 4,
    ];

    $form['purchased_entity[0][attributes][attribute_subscription_type]'] = [
      '#type' => 'hidden',
      '#default_value' => 9,
    ];

    $form['purchased_entity[0][attributes][attribute_gift]'] = [
      '#type' => 'hidden',
      '#default_value' => 11,
    ];

    $form['purchased_entity[0][attributes][attribute_publication]'] = [
      '#type' => 'hidden',
      '#default_value' => 12,
    ];

    $form['#action'] = '/store';

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Subscribe Now',
    ];

    return $form;

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}
