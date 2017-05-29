<?php

namespace Drupal\l10n_translate;

use Drupal\Core\Cache\DatabaseBackend;
use Drupal\Core\Config\ConfigFactoryInterface;
use Google\Cloud\Translate\TranslateClient;
use GuzzleHttp\Client;

/**
 * Class TranslationService.
 *
 * @package Drupal\l10n_translate
 */
class TranslationService implements TranslationInterface {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Drupal\Core\Cache\DatabaseBackend definition.
   *
   * @var \Drupal\Core\Cache\DatabaseBackend
   */
  protected $cache;

  /**
   * GuzzleHttp\Client definition.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * @var string
   *   A langcode string, e.g. en, de, fr.
   */
  protected $source;

  /**
   * @var string
   *   A langcode string, e.g. en, de, fr.
   */
  protected $target;

  /**
   * TranslationService constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   * @param \Drupal\Core\Cache\DatabaseBackend $cache
   * @param \GuzzleHttp\Client $http_client
   */
  public function __construct(
      ConfigFactoryInterface $config,
      DatabaseBackend $cache,
      Client $http_client
  ) {
    $this->config = $config;
    $this->cache = $cache;
    $this->httpClient = $http_client;

    $this->source = 'en';
    $this->target = 'de';
  }

  /**
   * {@inheritdoc}
   */
  public function setTarget($langcode) {
    $this->target = $langcode;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSource($langcode) {
    $this->source = $langcode;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function translate($string) {
    $config = $this->config->get('l10n.settings');

    $credentials_path = $config->get('credentials_path') ?
                        $config->get('credentials_path') :
                        __DIR__ . '/..';

    try {
      putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentials_path . '/credentials.json');
      $translate = new TranslateClient();
      $translation = $translate->translate(
        $string,
        [
          'target' => $this->target,
          'source' => $this->source,
        ]
      );
      return $translation;
    }
    catch (\Exception $e) {
      $error = json_decode($e->getMessage());
      throw new \Exception(
        $error->error->message,
        $error->error->code
      );
    }
  }

}
