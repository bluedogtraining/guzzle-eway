<?php

namespace Jwpage\Eway;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

class EwayClient extends Client
{
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp',
        );
        $config = Collection::fromConfig($config, $default);
        $description = ServiceDescription::factory(__DIR__.'/service.json');

        $client = new self($config->get('base_url'), $config);
        $client->setDescription($description);

        return $client;
    }
}
