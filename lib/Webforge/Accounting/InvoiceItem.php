<?php

namespace Psc\Data\Accounting;

interface InvoiceItem {
  
  /**
   * @return string
   */
  public function getLabel();
  
  /**
   * @return Price
   */
  public function getPrice();
  

  /**
   * Der "Pos" Eintrag der Rechnung
   * 
   * @return
   */
  public function getPos();
  
  
  /**
   * @chainable
   */
  public function setPos($pos);
}
?>