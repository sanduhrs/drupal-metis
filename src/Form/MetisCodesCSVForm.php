<?php

namespace Drupal\metis\Form;

use Drupal\Core\File\FileSystem;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Metis settings for this site.
 */
class MetisCodesCSVForm extends MetisCodesForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metis_codes_csv';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_csv'] = array(
      '#type' => 'file',
      '#title' => t('CSV file with Metis codes'),
      '#description' => t('Please upload the <abbr title="Comma Separated Values">CSV</abbr> file with metis codes.'),
      '#upload_location' => 'private://metis',
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    );

    $form = parent::buildForm($form, $form_state);
    unset($form['codes']);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $codes = [];
    // Attempt to save the uploaded file.
    $files = file_save_upload(
      'file_csv',
      [
        'file_validate_extensions' => ['csv'],
      ]
    );
    $file = array_pop($files);

    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = \Drupal::service('file_system');
    $input = file_get_contents($file_system->realpath($file->getFileUri()));

    // Get rid of white spaces.
    $input = trim(str_replace(" ", "", $input));
    // Get rid of newline variations.
    $input = str_replace("\r\n", "\n", $input);
    // Split by line.
    $rows = explode("\n", $input);

    $codes = [];
    foreach ($rows as $row) {
      $items = explode(';', $row);

      // Prepare array for validation.
      $codes[] = [
        'public' => trim($items[0]),
        'private' => trim($items[1]),
        'server' => $form_state->getValue('server'),
      ];
    }

    // Add validated codes to $form_state.
    $form_state->setValue('validated', $this->codeValidation($codes));
  }

}
