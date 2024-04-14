<?php

namespace Drupal\influxdb;

/**
 * Provides the InfluxDbClientFactory class.
 */
class InfluxDbClientFactory {

  /**
   * Provides the constructor method.
   */
  public function __construct(
    protected InfluxDbConfigServiceInterface $service,
  ) {
  }

  /**
   * Gets a client.
   *
   * @return \Drupal\influxdb\InfluxDbClientInterface
   *   The client.
   */
  public function get(): InfluxDbClientInterface {
    $schema = $this->service->getSchema();
    $host = $this->service->getHost();
    $port = $this->service->getPort();

    $url = "$schema://$host:$port";

    return new InfluxDbClient([
      "url" => $url,
      "token" => $this->service->getToken(),
      "bucket" => $this->service->getBucket(),
      "org" => $this->service->getOrganization(),
      "httpClient" => $this->service->getHttpClient(),
      "precision" => $this->service->getPrecision(),
    ]);
  }

}
