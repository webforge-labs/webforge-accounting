<?php

namespace Webforge\Accounting;

use InvalidArgumentException;

/**
 * Das Objekt für einen Netto/Brutto Preis
 *
 * bis jetzt ist das Objekt readonly und das soll auch eignetlich so bleiben (Value Object immutable)
 * $item->setPrice(new Price(xxx)); wäre dann das vorgehen zum Wert ändern
 */
class Price {
  
  const NET = 'netto';
  const GROSS = 'brutto';
  const BRUTTO = self::GROSS;
  const NETTO = self::NET;
  const TAX = 'taxes';

  const NO_TAXES = -1;
  
  const GERMAN = 'format_german';
  
  /**
   * @var const NET|GROSS
   */
  protected $type;
  
  /**
   * @var float
   */
  protected $net;
  
  /**
   * @var float 1 = 100%
   */
  protected $tax;
  
  /**
   * @var int
   */
  protected $precision = 2;
  
  public function __construct($price, $type, $tax, $precision = 2) {
    $this->checkValue($type, self::NET, self::GROSS);
    
    if (!is_numeric($price)) {
      throw new InvalidArgumentException('Price must be numeric');
    }

    if ($tax !== -1 && (!is_float($tax) || ($tax <= 0))) {
      throw new InvalidArgumentException('Provice tax as positve float: 1 = 100%. Price::NO_TAXES for no taxes');
    }
    $this->tax = $tax;
    
    if ($this->tax === -1) {
      $this->net = $price;
    } elseif ($type === self::GROSS) {
      $this->net = $price / (1+$this->tax);
    } else {
      $this->net = $price;
    }
    
    $this->setPrecision($precision);
  }
  
  public function export() {
    return (object) array('net'=>$this->net, 'tax'=>$this->tax, 'type'=>$this->type);
  }
  
  public function getNet() {
    return $this->convertTo(self::NET);
  }

  public function getGross() {
    return $this->convertTo(self::GROSS);
  }
  
  public function getFormat($type, $style = self::GERMAN) {
    $float = $this->convertTo($type);
    return number_format($float, $this->precision, ',', '.');
  }
  
  /**
   * @return float
   */
  public function getTaxValue($precision = NULL) {
    return $this->convertTo(self::TAX);
  }
  
  public function convertTo($type, $precision = NULL) {
    $this->checkValue($type, self::NET, self::GROSS, self::TAX);
    if ($type === self::GROSS && $this->tax !== -1) {
      $price = $this->net * (1+$this->tax);
    } elseif ($type === self::TAX) {
      if ($this->tax === -1) {
        return 0;
      }
      $price = $this->net * $this->tax;
    } else {
      $price = $this->net;
    }
    return round($price, $precision ?: $this->precision);
  }
  
  /**
   * @param int $precision
   * @chainable
   */
  public function setPrecision($precision) {
    if (is_int($precision) && $precision > 0) {
      $this->precision = $precision;
      return $this;
    }
      
    throw new InvalidArgumentException('Precision has to be an integer greater than 0');
  }

  /**
   * @return int
   */
  public function getPrecision() {
    return $this->precision;
  }
  
  /**
   * Returns the current percent value for tax
   * 
   * returns 0 for no taxes
   * @return float 
   */
  public function getTax() {
    if ($this->tax === self::NO_TAXES) return 0;
    
    return $this->tax;
  }

  protected function checkValue($value) {
    $values = func_get_args();
    array_shift($values); // value entfernen

    if (!in_array($value,$values)) {
      throw new InvalidArgumentException('Value: "'.$value.'" is not an allowed value. Allowed are: ('.implode('|',$values).')');
    }

    return $value;
  }
}
