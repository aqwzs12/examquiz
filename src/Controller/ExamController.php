<?php

namespace Drupal\examquiz\Controller;

use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class ExamController.
 */
class ExamController extends ControllerBase {

  /**
   * Show.
   *
   * @return string
   *   Return Hello string.
   */
  public function show(Request $request) {
    
    $score = 0 ;
    $user_id = \Drupal::currentUser()->id();
    $params = $request->request->all();
    $exam = Node::load($params["node"]);
    foreach($exam->get("field_exam_questions")->getValue() as $question){
      $node= Node::load($question["target_id"]);
      $answers = $node->get("field_question_answers")->getValue();
      $score_question = $node->get("field_question_score")->getValue();
      if(TRUE){
        $score += $score_question ;
      }
      var_dump($score_question);
    }
    die;    
  }









}
