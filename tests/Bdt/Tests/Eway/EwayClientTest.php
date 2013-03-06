<?php

namespace Bdt\Test\Eway;

use Bdt\Eway\EwayClient;
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

    public function testCanSetCustomerIdInClient()
    {
        $this->client = EwayClient::factory(array(
            'base_url' => 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp',
            'customer_id' => 100 
        ));
        $command = $this->client->getCommand('SendPayment', array(
            'totalAmount'     => '10',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ));
        $command->prepare();
        $this->assertEquals(100, $command['customerID']);
    }

    public function testSendPayment()
    {
        $this->setMockResponse($this->client, 'sendpayment_success.txt');
        $response = $this->client->getCommand('SendPayment', array(
            'customerID'      => '87654321',
            'totalAmount'     => '10',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ))->execute();
        $this->assertEquals('10', $response['trxnError']['code']);
        $this->assertEquals('Approved For Partial Amount(Test CVN Gateway)', $response['trxnError']['message']);
        $this->assertTrue($response->get('trxnStatus'));
    }

    public function testSendPaymentFail()
    {
        $this->setMockResponse($this->client, 'sendpayment_failure.txt');
        $response = $this->client->getCommand('SendPayment', array(
            'customerID'      => '87654321',
            'totalAmount'     => '13',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ))->execute();
        $this->assertEquals('13', $response['trxnError']['code']);
        $this->assertEquals('Invalid Amount(Test CVN Gateway)', $response['trxnError']['message']);
        $this->assertFalse($response['trxnStatus']);


    }

}