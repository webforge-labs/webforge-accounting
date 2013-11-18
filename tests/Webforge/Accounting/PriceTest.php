<?php

namespace Webforge\Accounting;

class PriceTest extends \Webforge\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Webforge\Accounting\Price';
    parent::setUp();
    $this->price = new Price(73.90, Price::NETTO, 0.07);
  }
  
  /**
   * @dataProvider providePrices
   */
  public function testConstruct_withNetto($expectedBrutto, $expectedNetto, $tax = 0.19, $expectedTax = NULL) {
    $this->assertTaxFormula($expectedBrutto, $expectedNetto, $tax);
    
    $price = new Price($expectedNetto, Price::NETTO, $tax);

    if ($expectedTax === NULL) $expectedTax = $tax;
    
    $this->assertEquals($expectedBrutto, $price->convertTo(Price::BRUTTO));
    $this->assertEquals($expectedBrutto, $price->getGross());
    $this->assertEquals($expectedNetto, $price->convertTo(Price::NETTO));
    $this->assertEquals($expectedNetto, $price->getNet());
    $this->assertEquals($expectedTax, $price->getTax());
    $this->assertEquals($expectedBrutto-$expectedNetto, $price->getTaxValue());
  }
  
  protected function assertTaxFormula($expectedBrutto, $expectedNetto, $tax) {
    if ($tax === -1) {
      $this->assertEquals($expectedBrutto, $expectedNetto);
    } else {
      $this->assertEquals((float) round($expectedNetto + $expectedNetto * $tax, 2), (float) round($expectedBrutto, 2), 'Netto Brutto-Test-Werte sind falsch!');
    }
  }

  /**
   * @dataProvider providePrices
   */
  public function testConstruct_withBrutto($expectedBrutto, $expectedNetto, $tax = 0.19) {
    $this->assertTaxFormula($expectedBrutto, $expectedNetto, $tax);
    
    $price = new Price($expectedBrutto, Price::BRUTTO, $tax);
    
    $this->assertEquals($expectedBrutto, $price->convertTo(Price::BRUTTO));
    $this->assertEquals($expectedNetto, $price->convertTo(Price::NETTO));
  }

  public function testPricesWithNoTax() {
    $price = new Price(100, Price::NETTO, -1);

    $this->assertEquals(100, $price->convertTo(Price::NETTO));
    $this->assertEquals(100, $price->convertTo(Price::BRUTTO));
    $this->assertEquals(0, $price->convertTo(Price::TAX));
  }
  
  public static function providePrices() {
    return Array(
      array(4284, 3600, 0.19),
      array(73.90, 69.07, 0.07),
      array(0, 0, 0.19),
      array(73.90, 73.90, Price::NO_TAXES, 0),
      array(-73.90, -69.07, 0.07)
    );
  }
  
  /**
   * @dataProvider provideBadPrices
   */
  public function testBadPrices($price, $type, $tax) {
    $this->setExpectedException('InvalidArgumentException');
    new Price($price, $type, $tax);
  }
  
  public static function provideBadPrices() {
    return Array(
      array("string", Price::NETTO, 0.19),
      array(6400, 'wrong', 0.19),
      array(7234, Price::NETTO, 0),
      array(7234, Price::NETTO, 8)
    );
  }

  public function testExport() {
    $this->assertInternalType('object', $this->price->export());
  }

  public function testSetPrecisionWithWrongNumber() {
    $this->setExpectedException('InvalidArgumentException');
    $this->price->setPrecision(0);
  }

  public function testSetPrecisionWithNumber() {
    $this->price->setPrecision(2);

    $this->assertEquals(2, $this->price->getPrecision());
  }

  public function testUsageExample() {
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
  }

  public function testUsageWithoutTaxesExample() {
    $price = new Price(4284, Price::GROSS, Price::NO_TAXES);

    $this->assertEquals(4284, $price->getGross());
    $this->assertEquals(4284, $price->getNet());
    $this->assertEquals(0, $price->getTax());
    $this->assertEquals(0, $price->getTaxValue());
  }
}
