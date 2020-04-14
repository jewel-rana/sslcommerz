<?php
namespace Rajtika\SSLCommerz\Services;
use Illuminate\Support\Facades\Config;
use Rajtika\SSLCommerz\Services\SSLCommerzInterface;

class SSLCommerz implements SSLCommerzInterface
{
    protected static $apiUrl;
    protected static $storeId;
    protected static $storePassword;
    protected static $params;

    public function __construct()
    {
        self::$apiUrl = Config::get('sslcommerz.apiDomain');
        self::$storeId = Config::get('sslcommerz.credentials.store_id');
        self::$storePassword = Config::get('sslcommerz.credentials.store_password');
    }

    public static function setParams( array $params )
    {
        if( !empty( $params ) ) {
            
            $missinParams = array_diff_key(array_flip([
                'total_amount',
                'currency',
                'cus_name',
                'cus_email',
                'cus_phone',
                'shipping_method',
                'product_name',
                'product_category',
                'product_profile'
            ]), $params);

            if( $missinParams ) {
                dd( implode(', ', array_keys( $missinParams ) ) . ' is missing in your params.' );
            }
            self::$params = $params;

            self::verifyParams();
        }
    }

    public static function makePayment()
    {
        return self::$params;
        self::__handShake();
    }

    public static function validateTransaction( $trxID, $amount, $currency, $requestData )
    {
        return 'You are just call to validate transaction';
        self::__handShake();
    }

    private static function __handShake()
    {
        return 'You are just call to make request to merchant';
    }

    private static function verifyParams()
    {
        //if shipping method is YES then check shipping details provided
        if( self::$params['shipping_method'] == 'YES' ) {
            $shippingInfo = array_diff_key(array_flip([
                'total_amount', 
                'currency', 
                'cus_name', 
                'cus_email', 
                'cus_phone', 
                'shipping_method',
                'product_name', 
                'product_category', 
                'product_profile'
            ]), self::$params);

            if( $shippingInfo ) {
                dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
            }
        } elseif( self::$params['product_profile'] == 'airline-vertical' ) {
            $shippingInfo = array_diff_key(array_flip([
                'total_amount', 
                'currency', 
                'cus_name', 
                'cus_email', 
                'cus_phone', 
                'shipping_method',
                'product_name', 
                'product_category', 
                'product_profile'
            ]), self::$params);

            if( $shippingInfo ) {
                dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
            }
        }
    }

    private static function __checkParams()
    {
        // return ( isset($arr['key1'], $arr['key2']) ) ? true : false;
    }
}