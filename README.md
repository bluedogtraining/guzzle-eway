# guzzle-eway

[![Build Status](https://travis-ci.org/bluedogtraining/guzzle-eway.png)](https://travis-ci.org/bluedogtraining/guzzle-eway)

A PHP 5.3+ Guzzle client for interacting with the Eway Direct Transaction API.

## Installation

Add this to your composer.json by running
`composer.phar require bluedogtraining/guzzle-eway`.

## Usage

### Create API client

```php
$client = \Bdt\Eway\EwayClient::factory();
```

### Send Payment

Command arguments are as defined in the [Eway API](http://www.eway.com.au/developers/api/direct-payments),
except without the `eway` prefix.

```php
$response = $client->getCommand('SendPayment', array(
    'customerID'      => '87654321',                                   
    'totalAmount'     => '10',                                         
    'cardHoldersName' => 'Foo Bar',                                    
    'cardNumber'      => '4444333322221111',                           
    'cardExpiryMonth' => '06',                                         
    'cardExpiryYear'  => '20',                                         
    'CVN'             => '123',
))->execute();

$response['trxnStatus']; // true
$response['trxnError']['code']; // 10
```

## Running Tests

First, install PHPUnit with `composer.phar install --dev`, then run
`./vendor/bin/phpunit`.

## More Reading

* [guzzlephp.org: Consuming web services using web service clients](http://guzzlephp.org/webservice-client/webservice-client.html)
* [eway.com.au: Response Codes](http://www.eway.com.au/developers/resources/response-codes)
* [eway.com.au: Direct Payments Sandbox](http://www.eway.com.au/developers/sandbox/direct-payments)
* [eway.com.au: Direct Payments Documentation](http://www.eway.com.au/developers/api/direct-payments)
