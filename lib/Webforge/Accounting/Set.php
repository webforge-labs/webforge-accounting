<?php

namespace Webforge\Accounting;

use Webforge\Common\String as S;
use Webforge\Types\Type;

class Set {

  protected $fields = array();
  protected $values = array();

  public function setType($fieldName, Type $fieldType) {
    $this->fields[$fieldName] = $fieldType;
  }

  public function __call($method, array $params = array()) {
    $prop = mb_strtolower(mb_substr($method,3,1)).mb_substr($method,4); // ucfirst in mb_string
    
    if (S::startsWith($method,'get')) {
      
      return $this->get($prop);
    
    } elseif (S::startsWith($method, 'set')) {
      
      return $this->set($prop, $params[0]);
    
    } else {
      throw new \Psc\Exception('Undefined Method: '.__CLASS__.'::'.$method);
    }
  }

  public function get($fieldName) {
    return $this->values[$fieldName];
  }

  public function set($fieldName, $value = NULL) {
    $this->values[$fieldName] = $value;
  }
}