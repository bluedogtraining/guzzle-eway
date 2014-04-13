<?php

namespace Bdt\Eway;

use GuzzleHttp\Subscriber\Log\Formatter as DefaultFormatter;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\Stream;

/**
 * Eway Formatter that hides the credit card number from logs.
 */
class Formatter extends DefaultFormatter
{
    /**
     * {@inheritdoc}
     * Replaces the ewayCardNumber field in the body with "X"s.
     */
    public function format(
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $error = null,
        array $customData = array()
    ) {
        $body = $request->getBody()->__toString();

        $newBody = preg_replace_callback('/<ewayCardNumber>(.*?)<\/ewayCardNumber>/', function ($matches) {
            $privateNumber = str_repeat('X', strlen($matches[1])-4).substr($matches[1], -4);
            return '<ewayCardNumber modified>'.$privateNumber.'</ewayCardNumber>';
        }, $body);
        $newRequest = clone $request;
        $newRequest->setBody(Stream::factory($newBody));

        $request->setBody(Stream::factory($body));

        return parent::format($newRequest, $response, $error, $customData);
    }
}
