<?php

namespace Drupal\metis\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Metis settings for this site.
 */
class SettingsForm extends ConfigFormBase {

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
    $form['metis_default_server'] = [
      '#type' => 'select',
      '#title' => $this->t('Default Metis server'),
      '#description' => $this->t('Please select the default Metis server to be used when uploading codes. If your website is registered with VG Wort as an editorial, you will be assigned a server that you should use permanently.'),
      '#default_value' => $this->config('metis.settings')->get('metis_default_server'),
      '#options' => array(
        'vg01.met.vgwort.de' => 'vg01.met.vgwort.de',
        'vg02.met.vgwort.de' => 'vg02.met.vgwort.de',
        'vg03.met.vgwort.de' => 'vg03.met.vgwort.de',
        'vg04.met.vgwort.de' => 'vg04.met.vgwort.de',
        'vg05.met.vgwort.de' => 'vg05.met.vgwort.de',
        'vg06.met.vgwort.de' => 'vg06.met.vgwort.de',
        'vg07.met.vgwort.de' => 'vg07.met.vgwort.de',
        'vg08.met.vgwort.de' => 'vg08.met.vgwort.de',
        'vg09.met.vgwort.de' => 'vg09.met.vgwort.de',
      ),
    ];
    $form['metis_force_ssl'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use SSL to include Metis codes'),
      '#description' => $this->t('Activate this option if you wish to use a secure connection (SSL) to include Metis codes. In that case the server ssl-vg03.met.vgwort.de will be used.'),
      '#default_value' => $this->config('metis.settings')->get('metis_force_ssl'),
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
