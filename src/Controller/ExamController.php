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
      $entity_id = $question["target_id"];
      $node= Node::load($entity_id);
      
      $answers = $this->extractAnswers($node->get("field_question_answers")->getValue());
      $score_question = $this->extractScore($node->get("field_question_score")->getValue());
          

      if($this->processAnswers($answers , $params["node_".$entity_id])){
        $score += $score_question ;
      }
      
    }
    var_dump($exam);die;
       
  }

// Return array of answers
public function extractAnswers($answers){
  $result = [];
  foreach($answers as $answer){
    $result[] = $answer['value'];
  }
  return $result;
}


// Return integer score 
public function extractScore($score){
  try{
    return $score[0]["value"];
  }catch(Exception $e) {
    return 0;
  }
}

public function processAnswers($answers , $user_answers){

  $user_answers_contaire[] = $user_answers;
  if($answer == $user_answers_contaire){
    return TRUE ;
  }
  return FALSE ; 
}


public function processExam($node, $uid, $score){
  $node->get("field_exam_users");

}











}
