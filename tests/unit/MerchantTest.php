<?php
namespace robokassa\tests\unit;

use robokassa\Merchant;

class MerchantTest extends TestCase
{
    public function testRedirectUrl()
    {
        $merchant = new Merchant([
            'sMerchantLogin' => 'demo',
            'sMerchantPass1' => 'password_1',
            'hashAlgo' => 'md5',
            'isTest' => true,
        ]);

        $returnUrl = $merchant->payment(100, 1, 'Description', null, null, 'en', [], true);

        $this->assertEquals("https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=demo&OutSum=100&InvId=1&Desc=Description&SignatureValue=8a50b8d86ed28921edfc371cff6e156f&Culture=en&IsTest=1", $returnUrl);

        // disable test
        $merchant->isTest = false;

        $returnUrl = $merchant->payment(100, 1, 'Description', null, null, 'en', [], true);

        $this->assertEquals("https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=demo&OutSum=100&InvId=1&Desc=Description&SignatureValue=8a50b8d86ed28921edfc371cff6e156f&Culture=en", $returnUrl);
    }

    public function testSignature()
    {
        $merchant = new Merchant([
            'sMerchantLogin' => 'demo',
            'sMerchantPass1' => 'password_1',
            'hashAlgo' => 'md5',
            'isTest' => true,
        ]);

        $signature = md5('100:1:pass1'); // '1e8f0be69238c13020beba0206951535'

        $this->assertTrue($merchant->checkSignature($signature, 100, 1, 'pass1', []));
    }

    public function testSignatureUserParams()
    {
        $merchant = new Merchant([
            'sMerchantLogin' => 'demo',
            'sMerchantPass1' => 'password_1',
            'hashAlgo' => 'md5',
            'isTest' => true,
        ]);

        $signature = md5('100:1:pass1:shp_id=1:shp_login=user1'); // 'd2b1beae30b0c2586eb4b4a7ce23aedd'

        $this->assertTrue($merchant->checkSignature($signature, 100, 1, 'pass1', [
            'shp_id' => 1,
            'shp_login' => 'user1',
        ]));
    }

    public function testSignatureInvalidSortUserParams()
    {
        $merchant = new Merchant([
            'sMerchantLogin' => 'demo',
            'sMerchantPass1' => 'password_1',
            'hashAlgo' => 'md5',
            'isTest' => true,
        ]);

        $signatureInvalidSort = md5('100:1:pass1:shp_login=user1:shp_id=1');

        $this->assertFalse($merchant->checkSignature($signatureInvalidSort, 100, 1, 'pass1', [
            'shp_id' => 1,
            'shp_login' => 'user1',
        ]));
    }
}
