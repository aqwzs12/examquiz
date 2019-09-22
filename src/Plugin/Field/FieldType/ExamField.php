<?php

namespace Drupal\examquiz\Plugin\Field\FieldType;



use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

/**
 * Plugin implementation of the 'exam_field' field type.
 *
 * @FieldType(
 *   id = "exam_field",
 *   label = @Translation("Exam field"),
 *   description = @Translation("Exam Field Type"), 
 *   category = @Translation("Custom"),
 *   default_widget = "entity_reference_autocomplete",
 *   default_formatter = "entity_reference_label",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList"
 *  )
 */
class ExamField extends EntityReferenceItem
{



  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
  {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties= parent::propertyDefinitions($field_definition);

    $properties['score'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('score'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition)
  {

    $schema = parent::schema($field_definition);
    $schema['columns']['score'] = array(
      'type' => 'int',
      'size' => 'tiny',
      'unsigned' => TRUE,
    );

    return $schema;
  }


  /**
   * {@inheritdoc}
   */
  public function isEmpty()
  {
    $score = $this->get('score')->getValue();
  
    return ($score === NULL || $score === '') ;
  }
}
