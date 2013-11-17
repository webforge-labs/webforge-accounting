<?php

namespace Webforge\Accounting;

use Webforge\Collections\ArrayCollection;

/**
 * Wrapper um eine ArrayCollection für InvoiceItems
 */
class InvoiceItems {
  
  /**
   * @var ArrayCollection
   */
  protected $items;
  
  /**
   * AutoIncrement Pos
   */
  protected $aiPos = 0;
  
  public function __construct() {
    $this->items = new ArrayCollection();
  }
  
  /**
   * @param mixed $pos
   */ 
  public function addItem(InvoiceItem $item, $pos = NULL) {
    $pos = $pos ?: $this->getNextPosition(TRUE);
    $this->items[$pos] = $item->setPos($pos);
  }
  
  public function getNextPosition($increase = FALSE) {
    $nextPos = $this->aiPos+1;
    
    if ($increase) {
      $this->aiPos = $nextPos;
    }
    
    return $nextPos;
  }
  
  public function unwrap() {
    return $this->items;
  }
  
  public function toArray() {
    return $this->unwrap()->toArray();
  }
}
