<?php

namespace Drupal\metis\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'metis' field widget.
 *
 * @FieldWidget(
 *   id = "metis",
 *   label = @Translation("Metis"),
 *   field_types = {"metis"},
 * )
 */
class MetisWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['code_public'] = [
      '#type' => 'hidden',
      '#title' => $this->t('Public code'),
      '#default_value' => isset($items[$delta]->code_public) ? $items[$delta]->code_public : NULL,
    ];
    $element['code_private'] = [
      '#type' => 'hidden',
      '#title' => $this->t('Private code'),
      '#default_value' => isset($items[$delta]->code_private) ? $items[$delta]->code_private : NULL,
    ];
    $element['server'] = [
      '#type' => 'hidden',
      '#title' => $this->t('Server'),
      '#default_value' => isset($items[$delta]->server) ? $items[$delta]->server : NULL,
    ];
    $element['show'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include public metis code in article (enable count)'),
      '#default_value' => isset($items[$delta]->show) ? $items[$delta]->show : NULL,
      '#description' => '<p><b>' . $this->t('Please note: VG Wort requires at least 1800 characters for a text to be qualified for submission.') . '</b></p>',
    ];

    if (TRUE) {
      $element['show']['#description'] .=
        '<p>' .
          $this->t('Public metis code: %code', ['%code' => $items[$delta]->code_public]) . '<br /> ' .
          $this->t('Private metis code: %code', ['%code' => $items[$delta]->code_private]) . '<br /> ' .
          $this->t('Metis Server: %server', ['%server' => $items[$delta]->server]) .
        '</p>';
    }

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'metis-elements';
    $element['#attached']['library'][] = 'metis/metis';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['code_public'] === '') {
        $values[$delta]['code_public'] = NULL;
      }
      if ($value['code_private'] === '') {
        $values[$delta]['code_private'] = NULL;
      }
      if ($value['show'] === '') {
        $values[$delta]['show'] = NULL;
      }
      if ($value['server'] === '') {
        $values[$delta]['server'] = NULL;
      }
    }
    return $values;
  }

}
