<?php

namespace Drupal\influxdb;

/**
 * Provides the InfluxDB constants.
 */
class InfluxDbConstants {

  /**
   * Provides the source.
   */
  const SOURCE = 'influxdb';

  /**
   * Provides the settings key.
   */
  const SETTINGS = 'influxdb.settings';

  /**
   * Provides the schema.
   */
  public const SCHEMA = 'http';

  /**
   * Provides the host.
   */
  public const HOST = 'influxdb';

  /**
   * Provides the port.
   */
  public const PORT = 8086;

  /**
   * Provides the precision.
   */
  public const PRECISION = InfluxDbPrecision::S;

  /**
   * Provides the measurement.
   */
  public const MEASUREMENT = 'drupal_influxdb';

  /**
   * Provides the debug flag.
   */
  public const DEBUG = FALSE;

}
