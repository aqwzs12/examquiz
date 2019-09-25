<?php

namespace Drupal\examquiz\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ExamConfigForm.
 */
class ExamConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'examquiz.examconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'exam_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('examquiz.examconfig');
    $form['exam_message_success'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('This is a exam_message the exam message'),
      '#default_value' => $config->get('exam_message_success'),
    ];
    
    $form['exam_message_success_status'] = [
      '#type' => 'textfield',
      '#title' => $this->t('status of message'),
      '#description' => $this->t('Choices :[ status , warning , error]'),
      '#default_value' => "status",
    ];

    $form['exam_message_fail'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message fail'),
      '#description' => $this->t('This is the exam message case of failure'),
      '#default_value' => $config->get('exam_message_fail'),
    ];

    $form['exam_message_fail_status'] = [
      '#type' => 'textfield',
      '#title' => $this->t('status of message'),
      '#description' => $this->t('Choices :[ status , warning , error]'),
      '#default_value' => "status",
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('examquiz.examconfig')
      ->set('exam_message_success', $form_state->getValue('exam_message_success'))
      ->set('exam_message_fail', $form_state->getValue('exam_message_fail'))
      ->set('exam_message_success_status' , $form_state->getValue('exam_message_success_status'))
      ->set('exam_message_fail_status' , $form_state->getValue('exam_message_fail_status'))
      ->save();
  }

}
