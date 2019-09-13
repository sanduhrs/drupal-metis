<?php

namespace Drupal\metis\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Metis settings for this site.
 */
class MetisCodesForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metis_codes';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['metis.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('metis.settings');

    $form['code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Metis code'),
      '#description' => t('Please enter Metis codes you want to save in the format [public_code];[private_code]. <strong>One per line. Every code must be 32 letters long.</strong>'),
      '#cols' => 32,
      '#rows' => 20,
      '#required' => TRUE,
    ];
    $form['server'] = [
      '#type' => 'select',
      '#title' => $this->t('Metis Server'),
      '#description' => t('Please select a Metis server to be associated with the imported codes.'),
      '#default_value' => $config->get('metis_default_server'),
      '#options' => [
        'vg01.met.vgwort.de' => 'vg01.met.vgwort.de',
        'vg02.met.vgwort.de' => 'vg02.met.vgwort.de',
        'vg03.met.vgwort.de' => 'vg03.met.vgwort.de',
        'vg04.met.vgwort.de' => 'vg04.met.vgwort.de',
        'vg05.met.vgwort.de' => 'vg05.met.vgwort.de',
        'vg06.met.vgwort.de' => 'vg06.met.vgwort.de',
        'vg07.met.vgwort.de' => 'vg07.met.vgwort.de',
        'vg08.met.vgwort.de' => 'vg08.met.vgwort.de',
        'vg09.met.vgwort.de' => 'vg09.met.vgwort.de',
      ],
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Get rid of white spaces.
    $input = trim($form_state->getValue('code'));
    // Split by line.
    $rows = explode("\n", $input);
    // Remove any extra \r characters left behind.
    $rows = array_filter($rows, 'trim');

    $codes = [];
    foreach ($rows as $row) {
      $items = explode(';', $row);

      // Prepare array for validation.
      $codes[] = [
        'public' => SafeMarkup::checkPlain(trim($items[0])),
        'private' => SafeMarkup::checkPlain(trim($items[1])),
        'server' => $form_state['values']['server'],
      ];
    }

    // Add validated codes to $form_state.
    $form_state->setValue('validated', self::codeValidation($codes));

    if ($form_state->getValue('example') != 'example') {
      $form_state->setErrorByName('example', $this->t('The value is not correct.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('metis.settings')
      ->set('example', $form_state->getValue('example'))
      ->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Validate submitted codes.
   *
   * @param array $codes
   *   An array of arrays containing the code values. Valid arguments are:
   *     - 'public' => string containing the 32 letter public metis code.
   *     - 'private' => string containing the 32 letter private metis code.
   *
   * @return array
   *   An array with the validated codes split up into arrays:
   *     - 'ignored' => codes to ignore.
   *     - 'public_length' => public metis codes longer than 32 characters.
   *     - 'private_length' => private metis codes longer than 32 characters.
   *     - 'code_exists' => codes that already exists in db.
   *     - 'valid' => codes that are valid.
   *     - 'error_validaton' => the $codes variable that led to an error.
   */
  public static function codeValidation(array $codes) {
    $codes_validated = [];
    if (is_array($codes)) {
      // Patterns to be ignored.
      $patterns = [
        'Öffentlicher',
        'Identifikationscode',
        'Privater',
        'VG Wort',
      ];

      foreach ($codes as $code) {
        // Variable used to determine if code should be ignored.
        $ignore = FALSE;

        // Ignore codes that contain any of the above.
        foreach ($patterns as $pattern) {
          if (stripos($code['public'], $pattern) || stripos($code['private'], $pattern)) {
            // Save ignored codes in an array.
            $codes_validated['ignored'][] = $code;
            $ignore = TRUE;
            break;
          }
        }

        if (!$ignore) {
          $query = \Drupal::database()->select('metis', 'm');
          $query
            ->fields('m')
            ->condition('code_public', $code['public']);
          $count_query = $query->countQuery();
          $results = $count_query->execute()->fetchField();

          // Error if code is already in db.
          if ($results != 0) {
            $codes_validated['code_exists'][] = $code;
          }
          // Error if public metis code is too long.
          elseif (Unicode::strlen($code['public']) != 32) {
            $codes_validated['public_length'][] = $code;
          }
          // Error if private metis code is too long.
          elseif ($code['private'] && Unicode::strlen($code['private']) != 32) {
            $codes_validated['private_length'][] = $code;
          }
          // Error if server doesn't match.
          elseif (preg_match('#vg[0-9]{2}\.met\.vgwort\.de#', $code['server']) == 0) {
            $codes_validated['server'][] = $code;
          }
          // Valid code.
          else {
            $codes_validated['valid'][] = $code;
          }
        }
      }
    }
    else {
      $codes_validated['error_validaton'][] = $codes;
    }
    return $codes_validated;
  }

}
