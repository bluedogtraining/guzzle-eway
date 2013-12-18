<?php

namespace Bdt\Eway;

use Guzzle\Common\Collection;
use Guzzle\Common\Event;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Client for sending payments with the Eway Direct Transactions API.
 */
class EwayClient extends Client
{
 
    /**
     * Factory method to create a new EwayClient 
     *
     * The following array keys and values are available options:
     * - base_url: Base URL of web service
     *
     * @param array|Collection $config Configuration data
     *
     * @return self
     */
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

    /**
     * {@inheritDoc}
     */
    public function __construct($baseUrl = '', $config = null)
    {
        parent::__construct($baseUrl, $config);
        if ($config && !empty($config['customer_id'])) {
            $this->getEventDispatcher()->addListener('command.before_prepare', function(Event $event) use ($config) {
                $event['command']['customerID'] = $event['command']['customerID']  ?: $config['customer_id'];
            });
        }
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
