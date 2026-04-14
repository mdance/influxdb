<?php

namespace Drupal\influxdb;


use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Provides the InfluxDbGuzzleClient class.
 */
class InfluxDbGuzzleClient extends Client implements ClientInterface {

  /**
   * {@inheritDoc}
   */
  public function sendRequest(RequestInterface $request): ResponseInterface {
    return $this->send($request);
  }

  /**
   * Prevent crash when InfluxDB2 Client tries to stringify options for errors.
   */
  public function __toString(): string {
    return 'InfluxDbGuzzleClient';
  }

}
