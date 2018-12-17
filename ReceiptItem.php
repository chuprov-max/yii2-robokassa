<?php

namespace robokassa;

use yii\helpers\StringHelper;

class ReceiptItem
{
    const TAX_NONE = 'none';
    const TAX_VAT_0 = 'vat0';
    const TAX_VAT_10 = 'vat10';
    const TAX_VAT_18 = 'vat18';
    const TAX_VAT_20 = 'vat20';
    const TAX_VAT_110 = 'vat110';
    const TAX_VAT_118 = 'vat118';
    const TAX_VAT_120 = 'vat120';
    
    const PAYMENT_METHOD_FULL_PREPAYMENT = 'full_prepayment';
    const PAYMENT_METHOD_PREPAYMENT = 'prepayment';
    const PAYMENT_METHOD_ADVANCE = 'advance';
    const PAYMENT_METHOD_FULL_PAYMENT = 'full_payment';
    const PAYMENT_METHOD_PARTIAL_PAYMENT = 'partial_payment';
    const PAYMENT_METHOD_CREDIT = 'credit';
    const PAYMENT_METHOD_CREDIT_PAYMENT = 'credit_payment';
    
    const PAYMENT_OBJECT_COMMODITY = 'commodity';
    const PAYMENT_OBJECT_EXCISE = 'excise';
    const PAYMENT_OBJECT_JOB = 'job';
    const PAYMENT_OBJECT_SERVICE = 'service';
    const PAYMENT_OBJECT_INTELLECTUAL_ACTIVITY = 'intellectual_activity';
    const PAYMENT_OBJECT_PAYMENT = 'payment';
    const PAYMENT_OBJECT_AGENT_COMISSION = 'agent_commission';
    const PAYMENT_OBJECT_COMPOSITE = 'composite';
    const PAYMENT_OBJECT_ANOTHER = 'another';
    const PAYMENT_OBJECT_PROPERTY_RIGHT = 'property_right';
    
    /**
     * @var string 
     */
    public $name;
    
    /**
     * @var float 
     */
    public $sum;
    
    /**
     * @var float 
     */
    public $quantity;
    
    /**
     * @var string 
     */
    public $tax;
    
    /**
     * @var string
     */
    public $payment_method;
    
    /**
     * @var string
     */
    public $payment_object;
    
    
    /**
     * 
     * @param type $name
     * @param type $sum
     * @param type $quantity
     * @param type $tax
     * @param type $paymentMethod
     * @param type $paymentObject
     */
    public function __construct($name, $sum, $quantity, $tax, $paymentMethod, $paymentObject)
    {
        $nameWithoutQuotes = str_replace('"', "", $name);
        $this->name = StringHelper::truncate(htmlspecialchars($nameWithoutQuotes), 64, '');
        $this->sum = $sum;
        $this->quantity = $quantity;
        $this->tax = $tax;
        $this->payment_method = $paymentMethod;
        $this->payment_object = $paymentObject;
    }
}
