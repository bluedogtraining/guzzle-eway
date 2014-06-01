<?php

namespace Bdt\Test\Eway;

use Bdt\Eway\EwayClient;
use GuzzleHttp\Command\Event\CommandEvents;
use GuzzleHttp\Subscriber\Mock;

class EwayClientTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->client = EwayClient::factory(array(
            'base_url' => 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp',
        ));
        $this->mock = new Mock;
        $this->client->getHttpClient()->getEmitter()->attach($this->mock);
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
        CommandEvents::prepare($command, $this->client);
        $this->assertEquals(100, $command['customerID']);
    }

    public function testSendPayment()
    {
        $this->setMockResponse('sendpayment_success.txt');
        $response = $this->client->SendPayment(array(
            'customerID'      => '87654321',
            'totalAmount'     => '10',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ));
        $this->assertEquals('10', $response['trxnError']['code']);
        $this->assertEquals('Approved For Partial Amount(Test CVN Gateway)', $response['trxnError']['message']);
        $this->assertTrue($response['trxnStatus']);
    }

    public function testSendPaymentFail()
    {
        $this->setMockResponse('sendpayment_failure.txt');
        $response = $this->client->SendPayment(array(
            'customerID'      => '87654321',
            'totalAmount'     => '13',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ));
        $this->assertEquals('13', $response['trxnError']['code']);
        $this->assertEquals('Invalid Amount(Test CVN Gateway)', $response['trxnError']['message']);
        $this->assertFalse($response['trxnStatus']);
    }

    public function testSendPaymentFail2()
    {
        $this->setMockResponse('sendpayment_failure_2.txt');
        $response = $this->client->SendPayment(array(
            'customerID'      => '87654321',
            'totalAmount'     => '13',
            'cardHoldersName' => 'Foo Bar',
            'cardNumber'      => '4444333322221111',
            'cardExpiryMonth' => '06',
            'cardExpiryYear'  => '20',
            'CVN'             => '123',
        ));
        $this->assertEquals(null, $response['trxnError']['code']);
        $this->assertEquals('eWAY Error: Invalid Expiry Date. Your credit card has not been  billed for this transaction.',
            $response['trxnError']['message']
        );
        $this->assertFalse($response['trxnStatus']);
    }

    private function setMockResponse($file)
    {
        $file = file_get_contents(__DIR__.'/mock/'.$file);
        $this->mock->addResponse($file);
    }

}