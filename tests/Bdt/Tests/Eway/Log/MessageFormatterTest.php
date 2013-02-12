<?php

namespace Bdt\Test\Eway\Log;

use Bdt\Eway\Log\MessageFormatter;
use Guzzle\Http\Message\RequestFactory;

class EwayClientTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $requestString = file_get_contents(MOCK_BASE_PATH.'/sendpayment_request.txt');
        $request = RequestFactory::getInstance()->fromMessage($requestString);
        $formatter = new MessageFormatter('{req_body}');
        $tpl = $formatter->format($request);
        $this->assertContains(
            '<ewayCardNumber modified>XXXXXXXXXXXX1111</ewayCardNumber>',
            $tpl,
            'Formatted request body should hide the credit card number'
        );
        $this->assertContains(
            '<ewayCardNumber>4444333322221111</ewayCardNumber>',
            $request->getBody()->__toString(),
            'Original request should not be modified'
        );
    }
}