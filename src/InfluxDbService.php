<?php

namespace Drupal\influxdb;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\influxdb\Event\InfluxDbEvents;
use Drupal\influxdb\Event\InfluxDbGlobalTagsEvent;
use Drupal\influxdb\Event\InfluxDbMetricsEvent;
use GuzzleHttp\ClientInterface;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;
use InfluxDB2\WritePayloadSerializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the InfluxDbService class.
 */
class InfluxDbService implements InfluxDbServiceInterface {

  use StringTranslationTrait;

  /**
   * Provides the site configuration.
   */
  protected Config $siteConfig;

  /**
   * Provides the constructor method.
   */
  public function __construct(
    protected EventDispatcherInterface $eventDispatcher,
    protected ConfigFactoryInterface $configFactory,
    protected InfluxDbConfigServiceInterface $configService,
    protected InfluxDbClientInterface $client,
    ) {
    $this->siteConfig = $this->configFactory->get('system.site');
  }

  /**
   * {@inheritDoc}
   */
  public function getLogging(): bool {
    return $this->configService->getLogging();
  }

  /**
   * {@inheritDoc}
   */
  public function getSchema(): string {
    return $this->configService->getSchema();
  }

  /**
   * {@inheritDoc}
   */
  public function getHost(): string {
    return $this->configService->getHost();
  }

  /**
   * {@inheritDoc}
   */
  public function getPort(): int {
    return $this->configService->getPort();
  }

  /**
   * {@inheritDoc}
   */
  public function getOrganization(): string {
    return $this->configService->getOrganization();
  }

  /**
   * {@inheritDoc}
   */
  public function getBucket(): string {
    return $this->configService->getBucket();
  }

  /**
   * {@inheritDoc}
   */
  public function getToken(): string {
    return $this->configService->getToken();
  }

  /**
   * {@inheritDoc}
   */
  public function getMeasurement(): string {
    return $this->configService->getMeasurement();
  }

  /**
   * {@inheritDoc}
   */
  public function getPrecision(): string {
    return $this->configService->getPrecision();
  }

  /**
   * {@inheritDoc}
   */
  public function getPrecisionOptions() : array {
    return $this->configService->getPrecisionOptions();
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultMetrics(): bool {
    return $this->configService->getDefaultMetrics();
  }

  /**
   * {@inheritDoc}
   */
  public function getDebug(): bool {
    return $this->configService->getDebug();
  }

  /**
   * {@inheritDoc}
   */
  public function saveConfiguration(array $input): InfluxDbServiceInterface {
    $this->configService->saveConfiguration($input);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getConfiguration(): array {
    return $this->configService->getConfiguration();
  }

  /**
   * {@inheritDoc}
   */
  public function getHttpClient(): ClientInterface {
    return $this->configService->getHttpClient();
  }

  /**
   * {@inheritDoc}
   */
  public function cron() {
    $event = new InfluxDbMetricsEvent();

    $template_point = $this->getTemplatePoint();

    $event->setTemplatePoint($template_point);

    $this->eventDispatcher->dispatch($event, InfluxDbEvents::METRICS->value);

    $points = $event->getPoints();
    $total = count($points);

    if (!$total) {
      return;
    }

    $write_api = $this->client->createWriteApi();

    $write_api->write($points);
  }

  /**
   * {@inheritDoc}
   */
  public function getTemplatePoint(): Point {
    static $global_tags = NULL;

    if (is_null($global_tags)) {
      $event = new InfluxDbGlobalTagsEvent();

      $this->eventDispatcher->dispatch($event, InfluxDbEvents::GLOBAL_TAGS->value);

      $global_tags = $event->getTags();
    }

    $output = new Point('drupal_influxdb');

    foreach ($global_tags as $key => $value) {
      $output->addTag($key, $value);
    }

    return $output;
  }

}
