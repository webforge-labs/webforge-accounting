<?php

namespace Psc\Data\Accounting;

use Psc\Data\PrototypeSet as Data;
use Psc\Data\Accounting\InvoiceItems;
use Webforge\Types\Type;

/**
 *
 * ein Huge DatenObjekt für die Element einer Invoice
 *
 * im Moment sehr einfach und sehr blöd implementiert.
 *
 *
 * $invoice->getData()->getDateTime()
 * $invoice->getPerson()->get('address.zip');
 * $invoice->getRecipient()->get('company.co');
 *   
 * $invoice->getItems(); //(=> InvoiceItems
 *
 * Die Felder die mit get() auf data/person/recipient zugegriffen werden können sieht man in den static create() functions
 * siehe auch Test für Constructor
 */
class Invoice extends \Psc\SimpleObject {
  
  protected $person;
  
  protected $recipient;
  
  protected $data;
  
  protected $items;
  
  public function __construct(Data $personData,
                              Data $recipientData,
                              Data $invoiceData,
                              InvoiceItems $invoiceItems) {
    
    $this->items = $invoiceItems;
    $this->data = $invoiceData;
    $this->person = $personData;
    $this->recipient = $recipientData;
  }
  

  /**
   * @return object
   */
  public function getPerson() {
    return $this->person;
  }

  /**
   * @return object
   */
  public function getData() {
    return $this->data;
  }

  /**
   * @return object
   */
  public function getRecipient() {
    return $this->recipient;
  }

  public function getItems() {
    return $this->items;
  }
  
  public static function createPersonData(Array $data) {
    return self::createData($data, 'person');
  }

  public static function createRecipientData(Array $data) {
    return self::createData($data, 'recipient');
  }

  public static function createInvoiceData(Array $data) {
    return self::createData($data, 'invoice');
  }

  protected static function createData(Array $data, $spec) {
    $set = new Data();
    
    $f = function ($field, $type = 'String') use (&$set) {
      $set->setFieldType($field, Type::create($type));
    };
    
    if ($spec === 'person') {
      $f('firstName');
      $f('name');
      $f('telephone');
      $f('company.title');
      $f('address.street');
      $f('address.zip', 'PositiveInteger');
      $f('address.countryCode');
      $f('address.city');
      $f('taxId');
    } elseif ($spec === 'recipient') {
      $f('company.title');
      $f('company.department');
      $f('company.co');
      $f('address.street');
      $f('address.zip', 'PositiveInteger');
      $f('address.countryCode');
      $f('address.city');
    } elseif ($spec === 'invoice') {
      $f('dateTime', 'DateTime');
      $f('labelId');
      $f('place'); // wenn nicht gesetzt wird person address city genommen
      $f('performancePeriod');
      $f('text', 'MarkupText');
    } else {
      throw new \Psc\Exception('unbekannte spec: '.$spec);
    }
    
    // validiert ein bissl
    foreach ($data as $field => $value) {
      $set->set($field, $value);
    }
    
    return $set;
  }
}
