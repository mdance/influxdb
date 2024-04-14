<?php

namespace Drupal\influxdb;

/**
 * Provides the InfluxDbPrecision enum.
 */
enum InfluxDbPrecision: string {
  const MS = 'ms';
  const S = 's';
  const US = 'us';
  const NS = 'ns';
}
