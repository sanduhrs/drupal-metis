<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Public metis code' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_code_public",
 *   label = @Translation("Public metis code"),
 *   field_types = {
 *     "metis"
 *   }
 * )
 */
class MetisPublicCodeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'metis_code_public',
        '#code_public' => $item->code_public,
        '#code_private' => $item->code_private,
        '#server' => $item->server,
        '#show' => $item->show,
      ];
    }

    return $element;
  }

}
