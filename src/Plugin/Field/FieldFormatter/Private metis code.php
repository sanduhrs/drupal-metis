<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Private metis code' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_code_private",
 *   label = @Translation("Private metis code"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class Private metis code extends FormatterBase {

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
