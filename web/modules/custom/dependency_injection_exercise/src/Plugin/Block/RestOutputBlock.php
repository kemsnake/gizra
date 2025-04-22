<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dependency_injection_exercise\DataFetchService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Data fetcher service.
   *
   * @var \Drupal\dependency_injection_exercise\DataFetchService
   */
  protected $dataFetcher;

  /**
   * Constructs a RestOutputBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\dependency_injection_exercise\DataFetchService $data_fetcher
   *   Data fetcher service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DataFetchService $data_fetcher) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dataFetcher = $data_fetcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_injection_exercise.data_fetcher'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    if ($data = $this->dataFetcher->get(random_int(1, 20))) {
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
