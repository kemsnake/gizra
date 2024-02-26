<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class DataFetchService {

  /**
   * The HTTP Client service.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * DataFetchService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The client used to fetch data.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function get(int $album_id = 5) {
    // Try to obtain the photo data via the external API.
    try {
      $response = $this->httpClient->request('GET', sprintf('https://jsonplaceholder.typicode.com/albums/%s/photos', $album_id));
      $raw_data = $response->getBody()->getContents();
      return Json::decode($raw_data);
    }
    catch (GuzzleException $e) {
      return FALSE;
    }

  }

}
