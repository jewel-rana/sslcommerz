<?php
namespace Rajtika\SslCommerz\Services;

interface SSLCommerzInterface
{
    public static function makePayment();

    public static function validateTransaction($trxID, $amount, $currency, $requestData);

    public static function setParams(array $data);
}