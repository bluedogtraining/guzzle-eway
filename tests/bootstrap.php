<?php

require __DIR__.'/../vendor/autoload.php';
define('MOCK_BASE_PATH', __DIR__.'/mock');
Guzzle\Tests\GuzzleTestCase::setMockBasePath(MOCK_BASE_PATH);
