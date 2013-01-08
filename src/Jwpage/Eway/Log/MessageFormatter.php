<?php

namespace Jwpage\Eway\Log;

use Guzzle\Log\MessageFormatter as GuzzleMessageFormatter;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;

/**
 * Eway MessageFormatter that hides the credit card number from logs.
 */
class MessageFormatter extends GuzzleMessageFormatter
{
    /**
     * {@inheritdoc}
     * Also replaces the ewayCardNumber field in the body with Xs.
     */
    public function format(
        RequestInterface $request,
        Response $response = null,
        CurlHandle $handle = null,
        array $customData = array()
    ) {
        if ($request instanceof EntityEnclosingRequestInterface) {
            $request = clone($request);
            $body = preg_replace_callback('/<ewayCardNumber>(.*?)<\/ewayCardNumber>/', function($matches) {
                $privateNumber = str_repeat('X', strlen($matches[1])-4).substr($matches[1], -4);
                return '<ewayCardNumber modified>'.$privateNumber.'</ewayCardNumber>';
            }, $request->getBody()->__toString());
            $request->setBody($body);
        }
        return parent::format($request, $response, $handle, $customData);
    }
}