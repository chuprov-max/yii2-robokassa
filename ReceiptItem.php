<?php

namespace robokassa;

use yii\helpers\StringHelper;

class ReceiptItem
{
    const TAX_NONE = 'none';
    const TAX_VAT_0 = 'vat0';
    const TAX_VAT_10 = 'vat10';
    const TAX_VAT_18 = 'vat18';
    const TAX_VAT_110 = 'vat110';
    const TAX_VAT_118 = 'vat118';
    
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
    
    public function __construct($name, $sum, $quantity, $tax)
    {
        $nameWithoutQuotes = str_replace('"', "", $name);
        $this->name = StringHelper::truncate(htmlspecialchars($nameWithoutQuotes), 64, '');
        $this->sum = $sum;
        $this->quantity = $quantity;
        $this->tax = $tax;
    }
}
