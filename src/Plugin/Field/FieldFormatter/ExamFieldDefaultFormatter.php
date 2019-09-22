<?php

namespace Drupal\examquiz\Plugin\Field\FieldFormatter;

use Drupal;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;

/**
 * Plugin implementation of the 'ExamFieldDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ExamFieldDefaultFormatter",
 *   label = @Translation("ExamField"),
 *   field_types = {
 *     "exam_field"
 *   }
 * )
 */
class ExamFieldDefaultFormatter extends EntityReferenceLabelFormatter {

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $values = $items->getValue();

    foreach ($elements as $delta => $entity) {
      $elements[$delta]['#suffix'] = ' x' . $values[$delta]['score'];
    }

    return $elements;
  }
}
