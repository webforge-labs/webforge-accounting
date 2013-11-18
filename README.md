# Accounting

[![Build Status](https://travis-ci.org/webforge-labs/webforge-accounting.png)](https://travis-ci.org/webforge-labs/webforge-accounting)  
[![Coverage Status](https://coveralls.io/repos/webforge-labs/webforge-accounting/badge.png?branch=master)](https://coveralls.io/r/webforge-labs/webforge-accounting?branch=master)  
[![Latest Stable Version](https://poser.pugx.org/webforge/accounting/version.png)](https://packagist.org/packages/webforge/accounting)  

A small library for some basics for invoices

## Prices

The `Webforge\Accounting\Price` class will help you with some calculation basics:

```php
$price = new Price(4284, Price::GROSS, 0.19);

$this->assertEquals(4284, $price->getGross());
$this->assertEquals(3600, $price->getNet());
$this->assertEquals(0.19, $price->getTax());
$this->assertEquals(684, $price->getTaxValue()); // = 4284-3600

// or construct it the other way round:
$price = new Price(3600, Price::NET, 0.19);
$this->assertEquals(4284, $price->getGross());
$this->assertEquals(3600, $price->getNet());
$this->assertEquals(0.19, $price->getTax());
$this->assertEquals(684, $price->getTaxValue()); // = 4284-3600
```

You can provide prices without taxes:
```php
$price = new Price(4284, Price::GROSS, Price::NO_TAXES);

$this->assertEquals(4284, $price->getGross());
$this->assertEquals(4284, $price->getNet());
$this->assertEquals(0, $price->getTax());
$this->assertEquals(0, $price->getTaxValue());
```
