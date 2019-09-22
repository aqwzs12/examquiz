<?php

namespace Drupal\examquiz\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\examquiz\Entity\ExamEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ExamEntityController.
 *
 *  Returns responses for Exam entity routes.
 */
class ExamEntityController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new ExamEntityController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Exam entity revision.
   *
   * @param int $exam_entity_revision
   *   The Exam entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($exam_entity_revision) {
    $exam_entity = $this->entityTypeManager()->getStorage('exam_entity')
      ->loadRevision($exam_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('exam_entity');

    return $view_builder->view($exam_entity);
  }

  /**
   * Page title callback for a Exam entity revision.
   *
   * @param int $exam_entity_revision
   *   The Exam entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($exam_entity_revision) {
    $exam_entity = $this->entityTypeManager()->getStorage('exam_entity')
      ->loadRevision($exam_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $exam_entity->label(),
      '%date' => $this->dateFormatter->format($exam_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Exam entity.
   *
   * @param \Drupal\examquiz\Entity\ExamEntityInterface $exam_entity
   *   A Exam entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ExamEntityInterface $exam_entity) {
    $account = $this->currentUser();
    $exam_entity_storage = $this->entityTypeManager()->getStorage('exam_entity');

    $langcode = $exam_entity->language()->getId();
    $langname = $exam_entity->language()->getName();
    $languages = $exam_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $exam_entity->label()]) : $this->t('Revisions for %title', ['%title' => $exam_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all exam entity revisions") || $account->hasPermission('administer exam entity entities')));
    $delete_permission = (($account->hasPermission("delete all exam entity revisions") || $account->hasPermission('administer exam entity entities')));

    $rows = [];

    $vids = $exam_entity_storage->revisionIds($exam_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\examquiz\ExamEntityInterface $revision */
      $revision = $exam_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $exam_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.exam_entity.revision', [
            'exam_entity' => $exam_entity->id(),
            'exam_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $exam_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.exam_entity.translation_revert', [
                'exam_entity' => $exam_entity->id(),
                'exam_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.exam_entity.revision_revert', [
                'exam_entity' => $exam_entity->id(),
                'exam_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.exam_entity.revision_delete', [
                'exam_entity' => $exam_entity->id(),
                'exam_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['exam_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
