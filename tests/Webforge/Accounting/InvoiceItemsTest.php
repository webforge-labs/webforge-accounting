<?php

namespace Webforge\Accounting;

class InvoiceItemsTest extends \Webforge\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Webforge\Accounting\InvoiceItems';
    parent::setUp();
  }
  
  public function testAcceptance() {
    $invoiceItems = new InvoiceItems();
    
    $invoiceItems->addItem($bananen = new SimpleInvoiceItem('mehrere Bananen (pauschalpreis)', new Price(2.50, Price::NETTO, 0.19)));
    $this->assertEquals(2, $invoiceItems->getNextPosition()); // does not increase
    $invoiceItems->addItem($aepfel = new SimpleInvoiceItem('Äpfel (pauschalpreis)', new Price(7.50, Price::BRUTTO, 0.19)));
    $this->assertCount(2, $invoiceItems->unwrap());
    
    $this->assertEquals(1, $bananen->getPos());
    $this->assertEquals(2, $aepfel->getPos());
    $this->assertEquals(array(1=>$bananen, 2=>$aepfel), $invoiceItems->toArray());
  }
}
