<?php

namespace Drupal\influxdb\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\influxdb\InfluxDbConstants;
use Drupal\influxdb\InfluxDbServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the InfluxDbAdminForm class.
 */
class InfluxDbAdminForm extends ConfigFormBase {

  /**
   * Provides the constructor method.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\influxdb\InfluxDbServiceInterface $service
   *   The module service.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    protected InfluxDbServiceInterface $service,
  ) {
    parent::__construct($configFactory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('influxdb'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'influxdb_admin';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [InfluxDbConstants::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['schema'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Schema'),
      '#default_value' => $this->service->getSchema(),
    ];

    $form['host'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Host'),
      '#default_value' => $this->service->getHost(),
    ];

    $form['port'] = [
      '#type' => 'number',
      '#title' => $this->t('Port'),
      '#default_value' => $this->service->getPort(),
      '#min' => 0,
    ];

    $form['organization'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Organization'),
      '#default_value' => $this->service->getOrganization(),
    ];

    $form['bucket'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Bucket'),
      '#default_value' => $this->service->getBucket(),
    ];

    $form['token'] = [
      '#type' => 'password',
      '#title' => $this->t('Token'),
      '#default_value' => $this->service->getToken(),
    ];

    $form['measurement'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Measurement'),
      '#default_value' => $this->service->getMeasurement(),
    ];

    $form['precision'] = [
      '#type' => 'select',
      '#title' => $this->t('Precision'),
      '#default_value' => $this->service->getPrecision(),
      '#options' => $this->service->getPrecisionOptions(),
    ];

    $form['default_metrics'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add default metrics'),
      '#default_value' => $this->service->getDefaultMetrics(),
    ];

    $form['logging'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Logging'),
      '#default_value' => $this->service->getLogging(),
    ];

    $form['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Debug'),
      '#default_value' => $this->service->getDebug(),
    ];

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->cleanValues()->getValues();

    $keys = [
      'token',
    ];

    foreach ($keys as $key) {
      if (empty($values[$key])) {
        unset($values[$key]);
      }
    }

    $this->service->saveConfiguration($values);

    parent::submitForm($form, $form_state);
  }

}
