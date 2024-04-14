<?php

namespace Drupal\influxdb\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\influxdb\InfluxDbClientInterface;
use InfluxDB2\Point;
use InfluxDB2\WriteType;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the InfluxDbTestForm class.
 */
class InfluxDbTestForm extends FormBase {

  /**
   * Provides the constructor method.
   *
   * @param \Drupal\influxdb\InfluxDbClientInterface $client
   *   The client.
   */
  public function __construct(
    protected InfluxDbClientInterface $client,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('influxdb_client'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'influxdb_test';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions'] = [
      '#type' => 'actions',
    ];

    $actions = &$form['actions'];

    $actions['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();

    $write_options = [
      'writeType' => WriteType::SYNCHRONOUS,
    ];

    $write_api = $this->client->createWriteApi($write_options);

    $p = new Point("networth");

    $p->addField("value", 100000);

    $write_api->write($p);
    $write_api->close();
  }

}
