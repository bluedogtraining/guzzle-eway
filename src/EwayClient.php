<?php

namespace Bdt\Eway;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Event\PrepareEvent;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;

/**
 * Client for sending payments with the Eway Direct Transactions API.
 */
class EwayClient extends GuzzleClient
{
 
    /**
     * {@inheritDoc}
     *
     * The additional array keys and values are available config options:
     * - base_url: Base URL of web service
     * - customer_id: a default customerID to use
     */
    public function __construct (Client $client, $config = array())
    {
        $descriptionJson = json_decode(file_get_contents(__DIR__.'/service.json'), true);
        if (empty($config['base_url'])) {
            $config['base_url'] = 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp';
        }
        $descriptionJson['baseUrl'] = $config['base_url'];

        $description = new Description($descriptionJson);
        parent::__construct($client, $description, $config);

        if (!empty($config['customer_id'])) {
            $this->getEmitter()->on('prepare', function (PrepareEvent $event) use ($config) {
                $command = $event->getCommand();
                if (empty($command['customerID'])) {
                    $command['customerID'] = $config['customer_id'];
                }
            }, 'first');
        }
    }

    /**
     * Shortcut to creating an EwayClient, when not passing a new Client.
     * 
     * @param array $config 
     * @return self
     */
    public static function factory($config = [])
    {
        $client = new Client();
        return new self($client, $config);
    }

    /**
     * Static method to split an error string into code and message.
     *
     * @param string $value error string in the format of "XX,Error message"
     * @return array An array containing the "code" and "message" of the error
     */
    public static function transformError($value)
    {
        $parts = explode(',', $value);

        if (2 !== count($parts)) {
            return array('code' => null, 'message' => $value);
        }

        return array_combine(array('code', 'message'), $parts);
    }
}
