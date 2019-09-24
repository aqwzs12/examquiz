<?php

namespace Drupal\examquiz\Controller;

use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class ExamController.
 */
class ExamController extends ControllerBase
{

  /**
   * Exam proccessig 
   */
  public function show(Request $request)
  {
    $score = 0;
    $user_id = \Drupal::currentUser()->id();
    $params = $request->request->all();
    $exam = Node::load($params["node"]);
    foreach ($exam->get("field_exam_questions")->getValue() as $question) {
      $entity_id = $question["target_id"];
      $node = Node::load($entity_id);
      $answers = $this->extractAnswers($node->get("field_question_answers")->getValue());
      $score_question = $this->extractScore($node->get("field_question_score")->getValue());
      if ($this->processAnswers($answers, $params["node_" . $entity_id])) {
        $score += $score_question;
      }
    }
    $exam_passed = $this->processExam($params["node"], $user_id, $score, $exam);
    if ($exam_passed) {
      var_dump("You Passed the exam succefully");
    } else {
      var_dump("You are dumb");
    }
    die;
    /* TODO: Not finished yet */
  }

  /**
   * Extract Answers 
   * @return array of answers
   */
  public function extractAnswers($answers)
  {
    $result = [];
    foreach ($answers as $answer) {
      $result[] = trim($answer['value']);
    }
    return $result;
  }


  /**
   *Extract Score
   *@return integer score 
   */
  public function extractScore($score)
  {
    try {
      return $score[0]["value"];
    } catch (Exception $e) {
      return 0;
    }
  }


  /**
   * Process Answers
   * Check if the answers provided by the user are correct
   * @return boolean 
   */
  public function processAnswers($answers, $user_answers)
  {
    if (is_array($user_answers)) {
      $user_answers_contaire = array_map('trim', $user_answers);
    } else {
      $user_answers_contaire[] = trim($user_answers);
    }
    sort($user_answers_contaire);
    sort($answers);
    if ($answers == $user_answers_contaire) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Process Exam
   * Save the date on the user side & exam side
   * 
   */
  public function processExam($nid, $uid, $score, $exam_node)
  {
    $user = User::load($uid);
    $score_to_pass = $this->extractScore($exam_node->get("field_exam_min_score")->getValue());
    $init_value = $user->get("field_passed_exams")->getValue();
    $result["target_id"] = $nid;
    $result["score"] = $score;
    $init_value[] = $result;
    $user->set("field_passed_exams", $init_value);
    $user->save();
    if ($score >= $score_to_pass) {
      return TRUE;
    }
    return FALSE;
  }
}
