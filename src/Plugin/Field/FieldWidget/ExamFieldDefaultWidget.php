<?php

namespace Drupal\examquiz\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;

/**
 * Plugin implementation of the 'ExamFieldDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "ExamFieldDefaultWidget",
 *   label = @Translation("Exam selector"),
 *   field_types = {
 *     "exam_field"
 *   }
 * )
 */
class ExamFieldDefaultWidget extends EntityReferenceAutocompleteWidget {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $widget = parent::formElement($items, $delta, $element, $form, $form_state);
  
    $widget['score'] = [
      '#type' => 'textfield',
      '#title' => t('Score'),
      '#default_value' => isset($items[$delta]) ? $items[$delta]->score : 1,
      '#min' => 1,
      '#weight' => 10,
    ];

    return $widget;
  }

}