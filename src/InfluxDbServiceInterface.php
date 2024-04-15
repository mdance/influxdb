<?php

namespace Drupal\influxdb;

use GuzzleHttp\ClientInterface;
use InfluxDB2\Point;

/**
 *
 */
interface InfluxDbServiceInterface {

  /**
   * Gets the logging status.
   *
   * @return bool
   *   The logging status.
   */
  public function getLogging(): bool;

  /**
   * Gets the schema.
   *
   * @return string
   *   The schema.
   */
  public function getSchema(): string;

  /**
   * Gets the host.
   *
   * @return string
   *   The host.
   */
  public function getHost(): string;

  /**
   * Gets the port.
   *
   * @return int
   *   The port.
   */
  public function getPort(): int;

  /**
   * Gets the organization.
   *
   * @return string
   *   The organization.
   */
  public function getOrganization(): string;

  /**
   * Gets the bucket.
   *
   * @return string
   *   The bucket.
   */
  public function getBucket(): string;

  /**
   * Gets the measurement.
   *
   * @return string
   *   The measurement.
   */
  public function getMeasurement(): string;

  /**
   * Gets the precision.
   *
   * @return string
   *   The precision.
   */
  public function getPrecision(): string;

  /**
   * Gets the default metrics flag.
   *
   * @return bool
   */
  public function getDefaultMetrics(): bool;

  /**
   * Gets the debug flag.
   *
   * @return bool
   *   The debug flag.
   */
  public function getDebug(): bool;

  /**
   * Saves the configuration.
   *
   * @param array $input
   *   The input.
   */
  public function saveConfiguration(array $input): InfluxDbServiceInterface;

  /**
   * Gets the configuration.
   *
   * @return array
   *   The configuration.
   */
  public function getConfiguration(): array;

  /**
   * Gets the HTTP client.
   *
   * @return \GuzzleHttp\ClientInterface
   *   The HTTP client.
   */
  public function getHttpClient(): ClientInterface;

  /**
   * Gets the precision options.
   *
   * @return array
   *   The precision options.
   */
  public function getPrecisionOptions(): array;

  /**
   * Performs cronjob processing.
   */
  public function cron();

  /**
   * Gets a template point.
   *
   * @return \InfluxDB2\Point
   *   The template point.
   */
  public function getTemplatePoint(): Point;

  /**
   * Gets the InfluxDb client.
   */
  public function getClient(): InfluxDbClientInterface;

}
