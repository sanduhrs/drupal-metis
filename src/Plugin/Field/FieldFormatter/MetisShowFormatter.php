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
 *     "metis"
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
        '#theme' => 'metis_show',
        '#code_public' => $item->code_public,
        '#code_private' => $item->code_private,
        '#server' => $item->server,
        '#show' => $item->show,
      ];
    }

    return $element;
  }

}
