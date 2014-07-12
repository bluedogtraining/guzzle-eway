CHANGELOG
=========

* 3.0.0 (2014-04-13)
    * Note: This release introduces a number of breaking changes.
    * Update directory structure to PSR-4 
    * Update to Guzzle 4
    * Remove 5.3 support
    * `EwayClient::__construct` parameters changed to Guzzle 4 `GuzzleHttp\Command\Guzzke\GuzzleClient` parameters
    * `Bdt\Eway\Log\MessageFormatter` is now `Bdt\Eway\Formatter`

* 2.1.4 (2014-01-28)
    * Add support for non-numeric eWay error messages
    * Support Guzzle 3.8.0

* 2.1.3 (2013-06-13)
    * Support Guzzle 3.7.0
    * Support Guzzle 3.6.0

* 2.1.2 (2013-05-24)
    * Support Guzzle 3.5.0
    * Add workaround for `EntityEnclosingRequest::__clone` issue
      (see https://github.com/guzzle/guzzle/issues/327)

* 2.1.1 (2013-04-15)
    * Support Guzzle 3.4.0

* 2.1.0 (2013-03-06)
    * Add `customer_id` config option to set as eWay customerID when no customerID is set.

* 2.0.1 (2013-03-05)
    * Support Guzzle 3.3.0

* 2.0.0 (2013-02-13)
    * Moved all classes from Jwpage to Bdt.

* 1.0.1 (2013-01-09)
    * Fix MessageFormatter::format declaration.

* 1.0.0 (2013-01-08)
    * Initial release.
