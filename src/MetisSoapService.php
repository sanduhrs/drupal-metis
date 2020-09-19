<?php

namespace Drupal\metis;

use Drupal\Core\Config\ConfigFactoryInterface;
use sanduhrs\Metis\MetisClientFactory;
use sanduhrs\Metis\Type\OrderPixelRequest;
use sanduhrs\Metis\Type\PixelOverviewRequest;

/**
 * MetisSoapService service.
 */
class MetisSoapService {

  const METIS_SERVICE_WSDL = 'https://tom.vgwort.de/services/1.0/pixelService.wsdl';

  const METIS_TYPE_01 = 'MINDESTZUGRIFF';

  const METIS_TYPE_02 = 'ANTEILIGER_MINDESTZUGRIFF';

  /**
   * The config object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The Metis SOAP client.
   *
   * @var \sanduhrs\Metis\MetisClientFactory
   */
  protected $client;

  /**
   * Constructs a MetisSoapService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('metis.settings');
    $this->client = MetisClientFactory::factory(
      self::METIS_SERVICE_WSDL,
      [
        'username' => $this->config->get('username'),
        'password' => $this->config->get('password'),
      ]
    );
  }

  /**
   * Order pixel.
   *
   * @param int $count
   *   The amount of pixels to order.
   *
   * @return \Phpro\SoapClient\Type\ResultInterface|\sanduhrs\Metis\Type\OrderPixelResponse
   *   The response object.
   */
  public function orderPixel($count = 0) {
    return $this->client->orderPixel(new OrderPixelRequest($count));
  }

  /**
   * Pixel overview.
   *
   * @param int $offset
   *   The offset used for paging.
   * @param string $type
   *   The pixel type.
   *
   * @return \Phpro\SoapClient\Type\ResultInterface|\sanduhrs\Metis\Type\PixelOverviewResponse
   */
  public function pixelOverview($offset = 0, $type = self::METIS_TYPE_01) {
    return $this->client->pixelOverview(new PixelOverviewRequest($offset, $type));
  }

}
