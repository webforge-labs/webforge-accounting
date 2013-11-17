<?php

namespace Webforge\Accounting;

class SimpleInvoiceItem implements InvoiceItem {
  
  protected $label;
  
  protected $price;
  
  protected $pos = NULL;
  
  public function __construct($label, Price $price) {
    $this->label = $label;
    $this->price = $price;
  }
  
  /**
   * @param mixed $pos
   * @chainable
   */
  public function setPos($pos) {
    $this->pos = $pos;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getPos() {
    return $this->pos;
  }


  
  /**
   * @param string $label
   * @chainable
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * @return string
   */
  public function getLabel() {
    return $this->label;
  }


  
  /**
   * @param Webforge\Accounting\Price $price
   * @chainable
   */
  public function setPrice(Price $price) {
    $this->price = $price;
    return $this;
  }

  /**
   * @return Webforge\Accounting\Price
   */
  public function getPrice() {
    return $this->price;
  }
}
