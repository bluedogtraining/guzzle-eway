<?php

namespace Bdt\Test\Eway;

use Bdt\Eway\Formatter;
use GuzzleHttp\Message\MessageFactory;

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $factory = new MessageFactory();

        $requestString = file_get_contents(MOCK_BASE_PATH.'/sendpayment_request.txt');
        $request = $factory->fromMessage($requestString);
        $formatter = new Formatter('{req_body}');
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