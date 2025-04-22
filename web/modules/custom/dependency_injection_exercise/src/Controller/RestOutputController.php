<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\dependency_injection_exercise\DataFetchService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the rest output.
 */
class RestOutputController extends ControllerBase {

  /**
   * Data fetcher service.
   *
   * @var \Drupal\dependency_injection_exercise\DataFetchService
   */
  protected $dataFetcher;

  /**
   * Constructs a RestOutputController object.
   *
   * @param \Drupal\dependency_injection_exercise\DataFetchService $data_fetcher
   *   Data fetcher service.
   */
  public function __construct(DataFetchService $data_fetcher) {
    $this->dataFetcher = $data_fetcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_injection_exercise.data_fetcher'),
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    if ($data = $this->dataFetcher->get()) {
      // Build a listing of photos from the photo data.
      $build['photos'] = array_map(static function ($item) {
        return [
          '#theme' => 'image',
          '#uri' => $item['thumbnailUrl'],
          '#alt' => $item['title'],
          '#title' => $item['title'],
        ];
      }, $data);
    }
    else {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];
    }

    return $build;
  }

}
