<?php

namespace Drupal\etype_subscribers\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User;

/**
 * Class EtypeSubscribersImportForm.
 */
class EtypeSubscribersImportForm extends FormBase {

  /**
   * Message.
   *
   * @var EtypeSubscribersImportForm
   */
  protected $message;

  /**
   * Get Form Id.
   *
   * @return string
   *   Form Id
   */
  public function getFormId() {
    return 'etype_subscribers_import_form';
  }

  /**
   * Build the Form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array
   *   Form
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $file_default_scheme = \Drupal::config('system.file')->get('default_scheme');

    $form['subscribers'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('CSV file of Subscribers'),
      '#upload_location' => $file_default_scheme . '://subscribers/',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
      '#required' => TRUE,
    ];

    $form['firstrow'] = [
      '#title' => $this->t('Check box if first row of file is field description.'),
      '#type' => 'checkbox',
      '#default_value' => 1,
    ];

    $form['deletebefore'] = [
      '#title' => $this->t('Check box to delete existing accounts where email address matches, and re-import the subscriber.'),
      '#type' => 'checkbox',
      '#default_value' => 0,
    ];

    $form['deleteall'] = [
      '#title' => $this->t('Check box to delete accounts where email address matches AND NOT RE-IMPORT.'),
      '#type' => 'checkbox',
      '#default_value' => 0,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Import Subscribers',
    ];

    return $form;
  }

  /**
   * Validate Form.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Submit Form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fid = $form_state->getValue('subscribers');
    $firstrow = $form_state->getValue('firstrow');
    $deletebefore = $form_state->getValue('deletebefore');
    $deleteall = $form_state->getValue('deleteall');
    if (count($fid) > 0) {
      $fileobj = File::load($fid[0]);
      $url = $fileobj->url();
      $firstrow == 1? $row = 0:$row = 1;
      if (($handle = fopen($url, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          // echo "<p> $num fields in line $row: <br /></p>\n";
          if ($row > 0) {
            // Data Order:
            // Password,ID,UserName,Email,FirstName,LastName,Phone1,Phone2,Address,City,State,Zip,SubscriptionExpiryDate,SubscriptionDate,PaymentStatus,Amount,SubscriptionPlan,RowNumber
            // var_dump($data);
            $email = $data[3];
            $subLevel = strtolower(trim($data[16]));
            $clean = Html::cleanCssIdentifier($subLevel);
            $role = str_replace(['-', '--', '__'], '_', $clean) . '_';
            // Delete User if they exist
            if ($user = user_load_by_mail($email)) {
              if ($deleteall == 1) {
                $user->delete();
                $this->message = "Account for $email deleted.";
                \Drupal::messenger()->addMessage($this->message);
              } elseif ($deletebefore == 1) {
                $user->delete();
                $this->message = "Account for $email deleted, and will be recreated.";
                \Drupal::messenger()->addMessage($this->message);
                $this->createUser($data, $role);
              } else {
                $this->message = "$email already has an account, skipping import.";
                \Drupal::messenger()->addMessage($this->message);
              }
            } else {
              $this->createUser($data, $role);
            }
          }
          $row++;
        }
        fclose($handle);
      }
      $file_usage = \Drupal::service('file.usage');
      $file_usage->delete($fileobj, $fid);
      $fileobj->delete($fileobj->id());
      // exit;
    }
  }

  private function createUser($data, $role) {
    $user = User::create();
    $user->setPassword($data[0]);
    $user->enforceIsNew();
    $user->setEmail($data[3]);
    $user->setUsername($data[2]);
    $user->addRole($role);
    $user->activate();
    try {
      $user->save();
    } catch (EntityStorageException $e) {
    }
    $this->message = "Account created for $data[3].";
    \Drupal::messenger()->addMessage($this->message);
  }
}
