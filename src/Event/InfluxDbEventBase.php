<?php

namespace Drupal\influxdb\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Provides the InfluxDbEventBase class.
 */
abstract class InfluxDbEventBase extends Event {

  /**
   * {@inheritDoc}
   */
  public function __construct() {
  }

}
