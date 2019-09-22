<?php

namespace Drupal\examquiz;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\examquiz\Entity\ExamEntityInterface;

/**
 * Defines the storage handler class for Exam entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Exam entity entities.
 *
 * @ingroup examquiz
 */
interface ExamEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Exam entity revision IDs for a specific Exam entity.
   *
   * @param \Drupal\examquiz\Entity\ExamEntityInterface $entity
   *   The Exam entity entity.
   *
   * @return int[]
   *   Exam entity revision IDs (in ascending order).
   */
  public function revisionIds(ExamEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Exam entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Exam entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\examquiz\Entity\ExamEntityInterface $entity
   *   The Exam entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ExamEntityInterface $entity);

  /**
   * Unsets the language for all Exam entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
