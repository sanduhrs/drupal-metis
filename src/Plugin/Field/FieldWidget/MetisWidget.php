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
      '#type' => 'textfield',
      '#title' => $this->t('Public code'),
      '#default_value' => isset($items[$delta]->code_public) ? $items[$delta]->code_public : NULL,
      '#size' => 20,
    ];

    $element['code_private'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Private code'),
      '#default_value' => isset($items[$delta]->code_private) ? $items[$delta]->code_private : NULL,
      '#size' => 20,
    ];

    $element['server'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Server'),
      '#default_value' => isset($items[$delta]->server) ? $items[$delta]->server : NULL,
      '#size' => 20,
    ];

    $element['used'] = [
      '#type' => 'textfield',
      '#title' => $this->t('used'),
      '#default_value' => isset($items[$delta]->used) ? $items[$delta]->used : NULL,
      '#size' => 20,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
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
      if ($value['server'] === '') {
        $values[$delta]['server'] = NULL;
      }
      if ($value['used'] === '') {
        $values[$delta]['used'] = NULL;
      }
    }
    return $values;
  }

}
