<?php

namespace Bdt\Eway\Log;

use Guzzle\Log\MessageFormatter as GuzzleMessageFormatter;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Curl\CurlHandle;

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
            $body = $request->getBody()->__toString();
            $newBody = preg_replace_callback('/<ewayCardNumber>(.*?)<\/ewayCardNumber>/', function($matches) {
                $privateNumber = str_repeat('X', strlen($matches[1])-4).substr($matches[1], -4);
                return '<ewayCardNumber modified>'.$privateNumber.'</ewayCardNumber>';
            }, $body);
            $newRequest = clone $request;
            $newRequest->setBody($newBody);

            $request->setBody($body);
        } else {
            $newRequest = $request;
        }
        return parent::format($newRequest, $response, $handle, $customData);
    }
}