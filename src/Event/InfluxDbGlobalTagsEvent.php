<?php

namespace Drupal\influxdb\Event;

/**
 * Provides the InfluxDbGlobalTagsEvent class.
 */
class InfluxDbGlobalTagsEvent extends InfluxDbEventBase {

  /**
   * Provides the tags.
   */
  protected array $tags;

  /**
   * {@inheritDoc}
   */
  public function __construct() {
  }

  /**
   * Adds a global tag.
   *
   * @param string $key
   *   The key.
   * @param string $value
   *   The value.
   *
   * @return self
   */
  public function add(string $key, string $value): self {
    $this->tags[$key] = $value;

    return $this;
  }

  /**
   * Gets the points.
   *
   * @return \InfluxDB2\Point[]
   *   The points.
   */
  public function getTags(): array {
    return $this->tags;
  }

}
