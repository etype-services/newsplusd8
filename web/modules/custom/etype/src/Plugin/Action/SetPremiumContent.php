<?php

namespace Drupal\etype\Plugin\Action;

use Drupal;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\Core\Entity\EntityStorageException;

/**
 * An example action covering most of the possible options.
 *
 * If type is left empty, action will be selectable for all
 * entity types.
 *
 * @Action(
 *   id = "set_premium_content",
 *   label = @Translation("Set node Premium Content status"),
 *   type = "node",
 *   confirm = FALSE,
 * )
 */
class SetPremiumContent extends ViewsBulkOperationsActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /*
     * All config resides in $this->configuration.
     * Passed view rows will be available in $this->context.
     * Data about the view used to select results and optionally
     * the batch context are available in $this->context or externally
     * through the public getContext() method.
     * The entire ViewExecutable object with selected result
     * rows is available in $this->view or externally through
     * the public getView() method.
     */

    $nid = $entity->id();
    $node = Drupal::entityTypeManager()->getStorage('node')->load($nid);

    if ($node instanceof NodeInterface) {
      try {
        $node->set('premium_content', $this->configuration['set_premium_content']);
        $node->save();
      }
      catch (EntityStorageException $e) {
        watchdog_exception('setpremium', $e);
      }
    }
    $this->messenger()->addMessage($entity->id());

  }

  /**
   * Configuration form builder.
   *
   * If this method has implementation, the action is
   * considered to be configurable.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return array
   *   The configuration form.
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array {
    $form['set_premium_content'] = [
      '#title' => t('Premium Content'),
      '#type' => 'checkbox',
      '#default_value' => $form_state->getValue('set_premium_content'),
    ];
    return $form;
  }

  /**
   * Submit handler for the action configuration form.
   *
   * If not implemented, the cleaned form values will be
   * passed directly to the action $configuration parameter.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // This is not required here, when this method is not defined,
    // form values are assigned to the action configuration by default.
    // This function is a must only when user input processing is needed.
    $this->configuration['set_premium_content'] = $form_state->getValue('set_premium_content');
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityType() === 'node') {
      $access = $object->access('update', $account, TRUE)
        ->andIf($object->status->access('edit', $account, TRUE));
      return $return_as_object ? $access : $access->isAllowed();
    }
    // Other entity types may have different
    // access methods and properties.
    return TRUE;
  }

}
