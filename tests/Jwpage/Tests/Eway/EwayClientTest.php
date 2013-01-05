<?php

namespace Jwpage\Test\Eway;

use Jwpage\Eway\EwayClient;
use Guzzle\Tests\GuzzleTestCase;
use Guzzle\Plugin\Log\LogPlugin;

class EwayClientTest extends GuzzleTestCase
{
    public function setup()
    {
        $this->client = EwayClient::factory(array(
            'base_url' => 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp',
        ));
    }

    public function testSendPayment()
    {
        $this->client->addSubscriber(LogPlugin::getDebugPlugin());
        $this->client->getCommand('SendPayment', array(
            'customerID'      => '87654321',
            'totalAmount'     => '10',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ))->execute();
    }

}