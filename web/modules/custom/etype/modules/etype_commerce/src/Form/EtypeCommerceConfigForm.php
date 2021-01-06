<?php

namespace Drupal\etype_commerce\Form {

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;
  use MailchimpMarketing\ApiClient;
  use MailchimpMarketing\ApiException;

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

      $mailchimp = new ApiClient();

      $mailchimp->setConfig([
        'apiKey' => $this->conf->get('MailChimpAPIKey'),
        'server' => $this->conf->get('MailChimpServerPrefix'),
      ]);

      try {
        $response = $mailchimp->ping->get();
      }
      catch (ApiException $e) {
        echo $e->getMessage();
      }

      $list_id = "bd3273b6d1";
      $email = "charlie@etypeservices.com";
      $subscriber_hash = md5($email);

      try {
        $response = $mailchimp->lists->setListMember($list_id, $subscriber_hash, [
          "email_address" => $email,
          "status_if_new" => "subscribed",
          "merge_fields" => [
            "FNAME" => "Prudence",
            "LNAME" => "McVankab",
          ],
        ]);
        print_r($response);
      }
      catch (ApiException $e) {
        echo $e->getMessage();
      }

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
        ->save();

    }

  }
}
