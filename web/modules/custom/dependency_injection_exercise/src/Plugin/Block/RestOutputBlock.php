<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase {

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

    // Try to obtain the photo data via the external API.
    try {
      $response = \Drupal::httpClient()->request('GET', sprintf('https://jsonplaceholder.typicode.com/albums/%s/photos', random_int(1, 20)));
      $raw_data = $response->getBody()->getContents();
      $data = Json::decode($raw_data);
    }
    catch (GuzzleException $e) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];
      return $build;
    }

    // Build a listing of photos from the photo data.
    $build['photos'] = array_map(static function ($item) {
      return [
        '#theme' => 'image',
        '#uri' => $item['thumbnailUrl'],
        '#alt' => $item['title'],
        '#title' => $item['title'],
      ];
    }, $data);

    return $build;
  }

}
