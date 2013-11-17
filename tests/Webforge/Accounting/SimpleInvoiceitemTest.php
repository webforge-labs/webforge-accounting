<?php

namespace Webforge\Accounting;

class SimpleInvoiceitemTest extends \Webforge\Code\Test\Base {
  
  public function setUp() {
    $this->chainClass = 'Webforge\Accounting\SimpleInvoiceItem';
    parent::setUp();
  }
  
  public function testConstruct() {
    $item = new SimpleInvoiceItem($l = 'mehrere Bananen (pauschalpreis)', $p = new Price(2.50, Price::NETTO, 0.19));
    
    $this->assertInstanceOf('Webforge\Accounting\InvoiceItem', $item);
    
    
    $this->assertSame($p, $item->getPrice());
    $this->assertEquals($l, $item->getLabel());
    
    $this->assertChainable($item->setPos(2));
    $this->assertEquals(2, $item->getPos());
  }
}
