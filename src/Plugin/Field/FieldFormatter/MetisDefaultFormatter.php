<?php

namespace Drupal\metis\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'metis_default' formatter.
 *
 * @FieldFormatter(
 *   id = "metis_default",
 *   label = @Translation("Metis pixel as image"),
 *   field_types = {"metis"}
 * )
 */
class MetisDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $config = \Drupal::config('metis.settings');

    foreach ($items as $delta => $item) {
      if (!$item->show) {
        break;
      }

      // Use SSL server if option to use SSL is set.
      $protocol = $config->get('metis_force_ssl') ? 'https' : 'http';

      // Intentionally _not_ using a template function here.
      $element[$delta]['metis'] = [
        '#markup' => '<img src="' . $protocol . '://' . $item->server . '/na/' . $item->code_public . '" height="1" width="1" border="0"/>',
      ];
      return $element;
    }
    return $element;
  }

}
