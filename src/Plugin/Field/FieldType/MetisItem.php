<?php

namespace Drupal\metis\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'metis' field type.
 *
 * @FieldType(
 *   id = "metis",
 *   label = @Translation("Metis"),
 *   category = @Translation("General"),
 *   default_widget = "metis",
 *   default_formatter = "metis_default"
 * )
 */
class MetisItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->code_public !== NULL) {
      return FALSE;
    }
    elseif ($this->code_private !== NULL) {
      return FALSE;
    }
    elseif ($this->show == 1) {
      return FALSE;
    }
    elseif ($this->server !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['code_public'] = DataDefinition::create('string')
      ->setLabel(t('Public code'));
    $properties['code_private'] = DataDefinition::create('string')
      ->setLabel(t('Private code'));
    $properties['show'] = DataDefinition::create('boolean')
      ->setLabel(t('Show'));
    $properties['server'] = DataDefinition::create('string')
      ->setLabel(t('Server'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();
    // @todo Add more constrains here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'code_public' => [
        'type' => 'varchar',
        'length' => 32,
      ],
      'code_private' => [
        'type' => 'varchar',
        'length' => 32,
      ],
      'show' => [
        'type' => 'int',
        'size' => 'tiny',
      ],
      'server' => [
        'type' => 'varchar',
        'length' => 256,
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['code_public'] = $random->word(mt_rand(1, 32));
    $values['code_private'] = $random->word(mt_rand(1, 32));
    $values['show'] = (bool) mt_rand(0, 1);
    $values['server'] = implode('.', [
      'www.',
      $random->word(mt_rand(1, 248)),
      '.org',
    ]);
    return $values;
  }

}
