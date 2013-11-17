<?php

namespace Psc\Data\Accounting;

/**
 * @group class:Psc\Data\Accounting\Price
 */
class PriceTest extends \Psc\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Psc\Data\Accounting\Price';
    parent::setUp();
  }
  
  /**
   * @dataProvider providePrices
   */
  public function testConstruct_withNetto($expectedBrutto, $expectedNetto, $tax = 0.19) {
    $this->assertTaxFormula($expectedBrutto, $expectedNetto, $tax);
    
    $price = new Price($expectedNetto, Price::NETTO, $tax);
    
    $this->assertEquals($expectedBrutto, $price->convertTo(Price::BRUTTO));
    $this->assertEquals($expectedNetto, $price->convertTo(Price::NETTO));
  }
  
  protected function assertTaxFormula($expectedBrutto, $expectedNetto, $tax) {
    $this->assertEquals((float) round($expectedNetto + $expectedNetto * $tax, 2), (float) round($expectedBrutto, 2), 'Netto Brutto-Test-Werte sind falsch!');
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
      array(0, 0, 0.19)
    );
  }
  
  // @TODO test bad values
}
?>