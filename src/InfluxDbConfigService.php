<?php

namespace Drupal\influxdb;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
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
    protected StateInterface $state,
    protected ClientInterface $client,
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
    return $this->getConfiguration()['token'] ?? '';
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
    $keys = [
      'token',
    ];

    $state = $this->state->get(InfluxDbConstants::SETTINGS, []);

    foreach ($keys as $key) {
      if (isset($input[$key])) {
        $state[$key] = $input[$key];
        unset($input[$key]);
      }
    }

    $config = $this->configFactory->getEditable(InfluxDbConstants::SETTINGS);

    foreach ($input as $key => $value) {
      $config->set($key, $value);
    }

    $this->state->set(InfluxDbConstants::SETTINGS, $state);
    $config->save();

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getConfiguration(): array {
    $output = $this->config->get();
    $state = $this->state->get(InfluxDbConstants::SETTINGS, []);

    $output = array_merge($output, $state);

    return $output;
  }

  /**
   * {@inheritDoc}
   */
  public function getHttpClient(): ClientInterface {
    return $this->client;
  }

}
