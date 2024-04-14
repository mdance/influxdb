<?php

namespace Drupal\influxdb\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\influxdb\Event\InfluxDbEvents;
use Drupal\influxdb\Event\InfluxDbMetricsEvent;
use Drupal\influxdb\InfluxDbServiceInterface;
use InfluxDB2\WritePayloadSerializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the InfluxDbController class.
 */
class InfluxDbController extends ControllerBase {

  /**
   * Provides the constructor method.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   Provides the event dispatcher.
   *
   *   The client.
   */
  public function __construct(
    protected EventDispatcherInterface $eventDispatcher,
    protected InfluxDbServiceInterface $service,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher'),
      $container->get('influxdb'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function metrics() {
    $event = new InfluxDbMetricsEvent();

    $point = $this->service->getTemplatePoint();

    $event->setTemplatePoint($point);

    $this->eventDispatcher->dispatch($event, InfluxDbEvents::METRICS->value);

    $points = $event->getPoints();
    $total = count($points);

    if ($total) {
      $content = WritePayloadSerializer::generatePayload($points);
    }
    else {
      $content = '';
    }

    $headers = [
      'Content-Type' => 'text/plain',
    ];

    return new Response($content, 200, $headers);
  }

}
