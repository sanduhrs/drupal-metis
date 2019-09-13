<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Show metis pixel (yes/no)' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_show",
 *   label = @Translation("Show metis pixel (yes/no)"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class MetisShowFormatter extends FormatterBase {

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
