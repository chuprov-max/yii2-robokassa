<?php

namespace robokassa;

use yii\helpers\Json;

/**
 * @link https://docs.robokassa.ru/?&_ga=2.3545268.545403185.1531216637-1835172103.1530105565#6865 Robochecks feature
 */
class Receipt
{
    const SNO_OSN = 'osn';
    const SNO_USN_INCOME = 'usn_income';
    const SNO_USN_INCOME_OUTCOME = 'usn_income_outcome';
    const SNO_ENVD = 'envd';
    const SNO_ESN = 'esn';
    const SNO_PATENT = 'patent';
    
    public $sno;
    
    public $items = [];
    
    public function __construct($sno = self::SNO_OSN)
    {
        $this->sno = $sno;
    }
    
    public function addItem(ReceiptItem $receiptItem)
    {
        $this->items[] = $receiptItem;
    }
    
    public function getJson()
    {
        $array = [];
        $array['sno'] = $this->sno;
        $array['items'] = $this->items;
        
        return Json::encode($array);
    }
}
