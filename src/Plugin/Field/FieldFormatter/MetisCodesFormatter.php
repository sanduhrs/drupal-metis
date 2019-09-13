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
 *     "metis"
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
        '#theme' => 'metis_codes',
        '#code_public' => $item->code_public,
        '#code_private' => $item->code_private,
        '#server' => $item->server,
        '#show' => $item->show,
      ];
    }

    return $element;
  }

}
