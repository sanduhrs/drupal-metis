<?php

namespace Drupal\metis\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;

/**
 * Configure Metis settings for this site.
 */
class MetisCodesForm extends ConfigFormBase {
  use MessengerTrait;

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

    $form['codes'] = [
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
    $input = $form_state->getValue('codes');

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
    $validated_codes = $this->codeValidation($codes);
    $form_state->setValue('validated', $validated_codes);

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $codes = $form_state->getValue('validated');
    $this->codeSubmission($codes);
    parent::submitForm($form, $form_state);
  }

  /**
   * Validate codes.
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
   *     - 'error_validation' => the $codes variable that led to an error.
   */
  public function codeValidation(array $codes) {
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
            $codes['ignored'][] = $code;
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
            $codes['code_exists'][] = $code;
          }
          // Error if public metis code is too long.
          elseif (mb_strlen($code['public']) != 32) {
            $codes['public_length'][] = $code;
          }
          // Error if private metis code is too long.
          elseif ($code['private'] && mb_strlen($code['private']) != 32) {
            $codes['private_length'][] = $code;
          }
          // Error if server doesn't match.
          elseif (preg_match('#vg[0-9]{2}\.met\.vgwort\.de#', $code['server']) == 0) {
            $codes['server'][] = $code;
          }
          // Valid code.
          else {
            $codes['valid'][] = $code;
          }
        }
      }
    }
    else {
      $codes['error_validation'][] = $codes;
    }
    return $codes;
  }

  /**
   * Submit codes.
   *
   * @param array $codes
   *   An array of arrays containing the validated codes, valid arguments are:
   *     - 'public' => string containing the 32 letter public metis code;
   *     - 'private' => string containing the 32 letter private metis code;
   */
  public function codeSubmission($codes) {
    $count_submit = 0;
    $count_error = 0;

    // Submit valid codes.
    if (isset($codes['valid'])) {
      foreach ($codes['valid'] as $code) {
        try {
          $query = \Drupal::database()->insert('metis');
          $query->fields([
            'code_public' => $code['public'],
            'code_private' => $code['private'],
            'server' => $code['server'],
          ]);
          $query->execute();
          // No exception thrown; PDO thinks the record was inserted correctly.
          $count_submit++;
        }
        catch (\Exception $e) {
          $count_error++;
        }
      }
    }

    // Set confirmation message.
    if ($count_submit > 0) {
      $this->messenger()->addStatus(t('%count codes have been saved.', ['%count' => $count_submit]));
    }
    if (isset($codes['ignored']) && $count = count($codes['ignored'])) {
      $this->messenger()->addWarning(t('%count line(s) have been ignored.', ['%count' => $count]));
    }
    if (isset($codes['public_length']) && $count = count($codes['public_length'])) {
      $this->messenger()->addWarning(t('%count codes have not been saved because the public metis code is not 32 letters long.', ['%count' => $count]));
    }
    if (isset($codes['private_length']) && $count = count($codes['private_length'])) {
      $this->messenger()->addWarning(t('%count codes have not been saved because the private metis code is not 32 letters long.', ['%count' => $count]));
    }
    if (isset($codes['server']) && $count = count($codes['server'])) {
      $this->messenger()->addWarning(t("%count codes have not been saved because the server wasn't valid.", ['%count' => $count]));
    }
    if (isset($codes['code_exists']) && $count = count($codes['code_exists'])) {
      $this->messenger()->addWarning(t('%count codes have not been saved because they already existed.', ['%count' => $count]));
    }
    if (isset($codes['error_validation']) && $count = count($codes['error_validation'])) {
      $this->messenger()->addError(t('There have been %count validation errors.', ['%count' => $count]));
    }
    if ($count_error > 0) {
      $this->messenger()->addError(t('%count codes have not been saved because of an error.', ['%count' => $count_error]));
    }
  }

}
