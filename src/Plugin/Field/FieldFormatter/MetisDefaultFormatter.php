<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'metis_default' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_default",
 *   label = @Translation("Default"),
 *   field_types = {"metis"}
 * )
 */
class MetisDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {

      if ($item->code_public) {
        $element[$delta]['code_public'] = [
          '#type' => 'item',
          '#title' => $this->t('Public code'),
          '#markup' => $item->code_public,
        ];
      }

      if ($item->code_private) {
        $element[$delta]['code_private'] = [
          '#type' => 'item',
          '#title' => $this->t('Private code'),
          '#markup' => $item->code_private,
        ];
      }

      if ($item->server) {
        $element[$delta]['server'] = [
          '#type' => 'item',
          '#title' => $this->t('Server'),
          '#markup' => $item->server,
        ];
      }

      if ($item->used) {
        $element[$delta]['used'] = [
          '#type' => 'item',
          '#title' => $this->t('used'),
          '#markup' => $item->used,
        ];
      }

    }

    return $element;
  }

}
