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
    elseif ($this->server !== NULL) {
      return FALSE;
    }
    elseif ($this->used !== NULL) {
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
    $properties['server'] = DataDefinition::create('string')
      ->setLabel(t('Server'));
    $properties['used'] = DataDefinition::create('string')
      ->setLabel(t('used'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    $options['code_public']['NotBlank'] = [];

    $options['code_private']['NotBlank'] = [];

    $options['server']['NotBlank'] = [];

    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $constraints[] = $constraint_manager->create('ComplexData', $options);
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
        'length' => 255,
      ],
      'code_private' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'server' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'used' => [
        'type' => 'varchar',
        'length' => 255,
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

    $values['code_public'] = $random->word(mt_rand(1, 255));

    $values['code_private'] = $random->word(mt_rand(1, 255));

    $values['server'] = $random->word(mt_rand(1, 255));

    $values['used'] = $random->word(mt_rand(1, 255));

    return $values;
  }

}
