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

}
