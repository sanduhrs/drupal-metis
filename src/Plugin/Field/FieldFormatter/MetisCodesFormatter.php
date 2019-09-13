<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Metis codes' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_codes",
 *   label = @Translation("Metis codes"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class MetisCodesFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => $item->value,
      ];
    }

    return $element;
  }

}
