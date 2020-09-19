<?php

namespace Drupal\metis\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;

/**
 * Configure Metis settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  use MessengerTrait;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metis_settings';
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
    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager */
    $entity_field_manager = \Drupal::getContainer()->get('entity_field.manager');
    $field_map = $entity_field_manager->getFieldMapByFieldType('metis');

    // Display warning if no Metis field is configured.
    if (!isset($field_map['node'])) {
      $this->messenger()->addStatus($this->t('There is no Metis field configured. Please add a field of the type <em>Metis</em> to at least one content type to make this module work.'));
    }

    // Display warning if no unused codes left.
    if (metis_count_unused() == 0) {
      $this->messenger()->addError($this->t('There are no unused codes left. Please add some.'));
    }

    $form['metis_default_server'] = [
      '#type' => 'select',
      '#title' => $this->t('Default Metis server'),
      '#description' => $this->t('Please select the default Metis server to be used when uploading codes. If your website is registered with VG Wort as an editorial, you will be assigned a server that you should use permanently.'),
      '#default_value' => $this->config('metis.settings')->get('metis_default_server'),
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
    ];
    $form['metis_force_ssl'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use SSL to include Metis codes'),
      '#description' => $this->t('Activate this option if you wish to use a secure connection (SSL) to include Metis codes.'),
      '#default_value' => $this->config('metis.settings')->get('metis_force_ssl'),
    ];
    $form['status'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('<strong>Status:</strong> There are currently %count_codes unused codes left.', ['%count_codes' => metis_count_unused()]) . '</p>',
      '#weight' => -3,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('metis.settings')
      ->set('metis_default_server', $form_state->getValue('metis_default_server'))
      ->set('metis_force_ssl', $form_state->getValue('metis_force_ssl'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
