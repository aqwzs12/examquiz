<?php

namespace Drupal\examquiz\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Exam entity entities.
 *
 * @ingroup examquiz
 */
interface ExamEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Exam entity name.
   *
   * @return string
   *   Name of the Exam entity.
   */
  public function getName();

  /**
   * Sets the Exam entity name.
   *
   * @param string $name
   *   The Exam entity name.
   *
   * @return \Drupal\examquiz\Entity\ExamEntityInterface
   *   The called Exam entity entity.
   */
  public function setName($name);

  /**
   * Gets the Exam entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Exam entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Exam entity creation timestamp.
   *
   * @param int $timestamp
   *   The Exam entity creation timestamp.
   *
   * @return \Drupal\examquiz\Entity\ExamEntityInterface
   *   The called Exam entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Exam entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Exam entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\examquiz\Entity\ExamEntityInterface
   *   The called Exam entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Exam entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Exam entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\examquiz\Entity\ExamEntityInterface
   *   The called Exam entity entity.
   */
  public function setRevisionUserId($uid);

}
