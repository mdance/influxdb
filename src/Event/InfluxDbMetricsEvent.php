<?php

namespace Drupal\influxdb\Event;

use InfluxDB2\Point;

/**
 * Provides the InfluxDbMetricsEvent class.
 */
class InfluxDbMetricsEvent extends InfluxDbEventBase {

  /**
   * Provides the template point.
   */
  protected ?Point $templatePoint;

  /**
   * Provides the points.
   */
  protected array $points = [];

  /**
   * {@inheritDoc}
   */
  public function __construct() {
  }

  /**
   * Sets the template point.
   *
   * @param \InfluxDB2\Point $point
   *   The template point.
   *
   * @return $this
   */
  public function setTemplatePoint(Point $point): self {
    $this->templatePoint = $point;

    return $this;
  }

  /**
   * Gets a cloned template point.
   *
   * @return \InfluxDB2\Point|null
   *   A cloned template point.
   */
  public function getTemplatePoint(): ?Point {
    return $this->templatePoint ? clone $this->templatePoint : $this->templatePoint;
  }

  /**
   * Adds a point.
   *
   * @param \InfluxDB2\Point $point
   *   The point.
   *
   * @return self
   */
  public function addPoint(Point $point): self {
    $this->points[] = $point;

    return $this;
  }

  /**
   * Gets the points.
   *
   * @return \InfluxDB2\Point[]
   *   The points.
   */
  public function getPoints(): array {
    return $this->points;
  }

}
