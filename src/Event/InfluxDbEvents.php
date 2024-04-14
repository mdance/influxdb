<?php

namespace Drupal\influxdb\Event;

enum InfluxDbEvents: string {

  /**
   * Provides the global tags event.
   */
  case GLOBAL_TAGS = 'influxdb.global_tags';

  /**
   * Provides the metrics event.
   */
  case METRICS = 'influxdb.metrics';

}
