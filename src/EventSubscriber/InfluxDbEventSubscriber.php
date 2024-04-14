<?php

namespace Drupal\influxdb\EventSubscriber;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\influxdb\Event\InfluxDbEvents;
use Drupal\influxdb\Event\InfluxDbGlobalTagsEvent;
use Drupal\influxdb\Event\InfluxDbMetricsEvent;
use Drupal\influxdb\InfluxDbServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides the InfluxDbEventSubscriber class.
 */
class InfluxDbEventSubscriber implements EventSubscriberInterface {

  /**
   * Provides the site configuration.
   */
  protected Config $config;

  /**
   * Constructs a StacksFitchEventSubscriber object.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected InfluxDbServiceInterface $service,
  ) {
    $this->config = $this->configFactory->get('system.site');
  }

  /**
   * Provides the influxdb global tags event handler.
   *
   * @param \Drupal\influxdb\Event\InfluxDbGlobalTagsEvent $event
   *   The event object.
   */
  public function onInfluxDbGlobalTags(InfluxDbGlobalTagsEvent $event) {
    $value = $this->config->get('name');

    if (!empty($value)) {
      $event->add('site', $value);
    }
  }

  /**
   * Provides the influxdb metrics event handler.
   *
   * @param \Drupal\influxdb\Event\InfluxDbMetricsEvent $event
   *   The event object.
   *
   * @return void
   */
  public function onInfluxDbMetrics(InfluxDbMetricsEvent $event): void {
    if (!$this->service->getDefaultMetrics()) {
      return;
    }

    $point = $event->getTemplatePoint();

    $point->addTag('module', 'influxdb');
    $point->addTag('metric', 'system');

    $node_count = $this->entityTypeManager->getStorage('node')->getQuery()->count()->accessCheck(FALSE)->execute();

    $point->addField('nodes', $node_count);

    $user_count = $this->entityTypeManager->getStorage('user')->getQuery()->count()->accessCheck(FALSE)->execute();

    $point->addField('users', $user_count);

    $point->addField('memory', memory_get_usage());

    $event->addPoint($point);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      InfluxDbEvents::GLOBAL_TAGS->value => ['onInfluxDbGlobalTags'],
      InfluxDbEvents::METRICS->value => ['onInfluxDbMetrics'],
    ];
  }

}
