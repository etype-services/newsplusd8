<?php

namespace Drupal\etype_commerce\Form {

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Class eTypeConfigForm.
   *
   * @package Drupal\etype\Form
   */
  class EtypeCommerceConfigForm extends ConfigFormBase {

    /**
     * Config.
     *
     * @var EtypeCommerceConfigForm
     */
    protected $conf;

    /**
     * EtypeCommerceConfigForm constructor.
     */
    public function __construct() {
      parent::__construct($this->configFactory());
      $this->conf = $this->config('etype_commerce.settings');
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames(): array {
      return [
        'etype_commerce.settings',
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId(): string {
      return 'etype_commerce_admin_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) :array {

      $form['MailChimpAPIKey'] = [
        '#title' => $this->t('MailChimp API Key'),
        '#type' => 'textfield',
        '#default_value' => $this->conf->get('MailChimpAPIKey'),
      ];

      $form['MailChimpServerPrefix'] = [
        '#title' => $this->t('MailChimp Server Prefix'),
        '#type' => 'textfield',
        '#default_value' => $this->conf->get('MailChimpServerPrefix'),
      ];

      $form['MailChimpListId'] = [
        '#title' => $this->t('MailChimp List Id'),
        '#type' => 'textfield',
        '#default_value' => $this->conf->get('MailChimpListId'),
      ];

      $val = $this->conf->get('buyDigitalText');
      $form['buyDigitalText'] = [
        '#type' => 'text_format',
        '#title' => $this->t('HTML for Buy Digital Form'),
        '#cols' => '100',
        '#default_value' => $val['value'],
        '#format' => $val['format'],
      ];

      $val = $this->conf->get('buyPrintText');
      $form['buyPrintText'] = [
        '#type' => 'text_format',
        '#title' => $this->t('HTML for Buy Print Form'),
        '#cols' => '100',
        '#default_value' => $val['value'],
        '#format' => $val['format'],
      ];

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      parent::submitForm($form, $form_state);

      $this->config('etype_commerce.settings')
        ->set('MailChimpAPIKey', $form_state->getValue('MailChimpAPIKey'))
        ->set('MailChimpServerPrefix', $form_state->getValue('MailChimpServerPrefix'))
        ->set('MailChimpListId', $form_state->getValue('MailChimpListId'))
        ->set('buyDigitalText', $form_state->getValue('buyDigitalText'))
        ->set('buyPrintText', $form_state->getValue('buyPrintText'))
        ->save();

    }

  }
}
