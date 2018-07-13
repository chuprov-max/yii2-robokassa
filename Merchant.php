<?php

namespace robokassa;

use Yii;
use yii\base\Object;
use yii\helpers\Html;
use yii\helpers\Json;

class Merchant extends Object
{
    public $sMerchantLogin;

    public $sMerchantPass1;
    public $sMerchantPass2;

    public $isTest = false;

    public $baseUrl = 'https://auth.robokassa.ru/Merchant/Index.aspx';
    
    /**
     * @var string the content enclosed within the button tag. It will NOT be HTML-encoded.
     * Therefore you can pass in HTML code such as an image tag. If this is is coming from end users,
     * you should consider [[encode()]] it to prevent XSS attacks.
     */
    public $submitButtonContent = 'Pay';
    
    /**
     * @var array the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag for submit button. The values will be HTML-encoded using [[encode()]].
     * If a value is null, the corresponding attribute will not be rendered. 
     */
    public $submitButtonOptions = [];    
        

    public function payment($nOutSum, $nInvId, $sInvDesc = null, $sIncCurrLabel=null, $sEmail = null, $sCulture = null, $shp = [], $returnLink = false)
    {
        $url = $this->baseUrl;

        $signature = "{$this->sMerchantLogin}:{$nOutSum}:{$nInvId}:{$this->sMerchantPass1}";
        if (!empty($shp)) {
            $signature .= ':' . $this->implodeShp($shp);
        }
        $sSignatureValue = md5($signature);

        $url .= '?' . http_build_query([
            'MrchLogin' => $this->sMerchantLogin,
            'OutSum' => $nOutSum,
            'InvId' => $nInvId,
            'Desc' => $sInvDesc,
            'SignatureValue' => $sSignatureValue,
            'IncCurrLabel' => $sIncCurrLabel,
            'Email' => $sEmail,
            'Culture' => $sCulture,
            'IsTest' => (int)$this->isTest,
        ]);

        if (!empty($shp) && ($query = http_build_query($shp)) !== '') {
            $url .= '&' . $query;
        }
        
        if ( !$returnLink ){
            Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());
            return Yii::$app->response->redirect($url);
        } else {
            return $url;
        }
    }
    
    /**
     * @param type $nOutSum
     * @param type $nInvId
     * @param type $sInvDesc
     * @param type $sIncCurrLabel
     * @param type $sEmail
     * @param type $sCulture
     * @param type $shp
     * @param \robokassa\Receipt $receipt Receipt object to use `Robocheks`
     * 
     * @return string payment form to send POST request to $this->baseUrl
     */
    public function paymentPostForm($nOutSum, $nInvId, $sInvDesc = null, $sIncCurrLabel=null, $sEmail = null, $sCulture = null, $shp = [], Receipt $receipt = null)
    {
        Yii::$app->request->enableCsrfValidation = false;
        
        $signature = "{$this->sMerchantLogin}:{$nOutSum}:{$nInvId}";
        
        if ($receipt) {
            $receiptJsonUrlEncoded = urlencode(Json::encode($receipt));
            $signature .= ":{$receiptJsonUrlEncoded}";
        }
        
        $signature .= ":{$this->sMerchantPass1}";
        
        if (!empty($shp)) {
            $signature .= ':' . $this->implodeShp($shp);
        }
        
        $sSignatureValue = md5($signature);
        
        $form = Html::beginForm($this->baseUrl, 'post');
        $form .= Html::hiddenInput('MrchLogin', $this->sMerchantLogin);
        $form .= Html::hiddenInput('OutSum', $nOutSum);
        $form .= Html::hiddenInput('InvId', $nInvId);
        $form .= Html::hiddenInput('Desc', $sInvDesc);
        $form .= Html::hiddenInput('SignatureValue', $sSignatureValue);
        $form .= Html::hiddenInput('IncCurrLabel', $sIncCurrLabel);
        $form .= Html::hiddenInput('Email', $sEmail);
        $form .= Html::hiddenInput('Culture', $sCulture);
        $form .= Html::hiddenInput('IsTest', (int)$this->isTest);
        
        if ($receipt && $receiptJsonUrlEncoded) {
            $form .= Html::hiddenInput('Receipt', $receiptJsonUrlEncoded);
        }
        
        $form .= Html::submitButton($this->submitButtonContent, $this->submitButtonOptions);
        
        $form .= Html::endForm();
        return $form;
    }

    private function implodeShp($shp)
    {
        ksort($shp);
        foreach($shp as $key => $value) {
            $shp[$key] = $key . '=' . $value;
        }

        return implode(':', $shp);
    }

    public  function checkSignature($sSignatureValue, $nOutSum, $nInvId, $sMerchantPass, $shp)
    {
        $signature = "{$nOutSum}:{$nInvId}:{$sMerchantPass}";
        if (!empty($shp)) {
            $signature .= ':' . $this->implodeShp($shp);
        }
        return strtolower(md5($signature)) === strtolower($sSignatureValue);

    }
} 
