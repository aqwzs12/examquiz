<?php

namespace Drupal\examquiz\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'question_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "question_field_formatter",
 *   label = @Translation("Question field formatter"),
 *   field_types = {
 *     "string",
 *   },
 *   
 * )
 */
class QuestionFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
   
    foreach ($items as $delta => $item) {
      $node = $item->getEntity();
      $nid = $node->id();
      $is_multiple = $node->get("field_is_multiple")->getValue()[0]["value"];
     // $elements[$delta] = ['#markup' => $this->viewHelper($is_multiple, $item->value, $nid) ];
     $elements[$delta] = [
     '#type' => 'inline_template',
     '#template' => $this->viewHelper($is_multiple, $item->value, $nid),
     ] ;
    
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item)
  {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    
    return nl2br(Html::escape($item->value));
  }


  protected function viewHelper($is_multiple, $value, $nid)
  {
    $result = "";
    if ($is_multiple != "0") {
      $result = '<input type="checkbox" name="node_' . $nid . '[]" value="' . $value . '">
    ' . $value . '</input>';
    } else {
      $result = '<input type="radio" name="node_' . $nid . '" value="' . $value . '">
    ' . $value . '</input>';
    }

    return $result;
  }
        
}
