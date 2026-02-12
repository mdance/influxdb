<?php

namespace Drupal\influxdb;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\key\KeyRepositoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Provides the InfluxDbConfigService class.
 */
class InfluxDbConfigService implements InfluxDbConfigServiceInterface {

  use StringTranslationTrait;

  /**
   * Provides the config.
   */
  protected Config $config;

  /**
   * Provides the constructor method.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected ClientInterface $client,
    protected KeyRepositoryInterface $keyRepository,
  ) {
    $this->config = $configFactory->get(InfluxDbConstants::SETTINGS);
  }

  /**
   * {@inheritDoc}
   */
  public function getLogging(): bool {
    return $this->getConfiguration()['logging'] ?? TRUE;
  }

  /**
   * {@inheritDoc}
   */
  public function getSchema(): string {
    return $this->getConfiguration()['schema'] ?? InfluxDbConstants::SCHEMA;
  }

  /**
   * {@inheritDoc}
   */
  public function getHost(): string {
    return $this->getConfiguration()['host'] ?? InfluxDbConstants::HOST;
  }

  /**
   * {@inheritDoc}
   */
  public function getPort(): int {
    return $this->getConfiguration()['port'] ?? InfluxDbConstants::PORT;
  }

  /**
   * {@inheritDoc}
   */
  public function getOrganization(): string {
    return $this->getConfiguration()['organization'] ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function getBucket(): string {
    return $this->getConfiguration()['bucket'] ?? '';
  }

  /**
   * {@inheritDoc}
   */
  public function getToken(): string {
    $key_id = $this->config->get('token') ?: 'influxdb_token';
    $key = $this->keyRepository->getKey($key_id);

    if ($key) {
      return $key->getKeyValue() ?: '';
    }

    return '';
  }

  /**
   * Gets the Key entity ID for the token.
   *
   * @return string
   *   The Key entity ID.
   */
  public function getTokenKeyId(): string {
    return $this->config->get('token') ?: 'influxdb_token';
  }

  /**
   * {@inheritDoc}
   */
  public function getMeasurement(): string {
    return $this->getConfiguration()['measurement'] ?? InfluxDbConstants::MEASUREMENT;
  }

  /**
   * {@inheritDoc}
   */
  public function getPrecision(): string {
    return $this->getConfiguration()['precision'] ?? InfluxDbConstants::PRECISION;
  }

  /**
   * {@inheritDoc}
   */
  public function getPrecisionOptions() : array {
    return [
      InfluxDbPrecision::S => $this->t('Seconds'),
      InfluxDbPrecision::MS => $this->t('Milliseconds'),
      InfluxDbPrecision::US => $this->t('Microseconds'),
      InfluxDbPrecision::NS => $this->t('Nanoseconds'),
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultMetrics(): bool {
    return $this->getConfiguration()['default_metrics'] ?? TRUE;
  }

  /**
   * {@inheritDoc}
   */
  public function getDebug(): bool {
    return $this->getConfiguration()['debug'] ?? InfluxDbConstants::DEBUG;
  }

  /**
   * {@inheritDoc}
   */
  public function saveConfiguration(array $input): self {
    $config = $this->configFactory->getEditable(InfluxDbConstants::SETTINGS);

    foreach ($input as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getConfiguration(): array {
    return $this->config->get() ?: [];
  }

  /**
   * {@inheritDoc}
   */
  public function getHttpClient(): ClientInterface {
    return $this->client;
  }

}
