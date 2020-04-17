<?php
namespace Rajtika\SSLCommerz\Services;
use Illuminate\Support\Facades\Config;
use Rajtika\SSLCommerz\Services\SSLCommerzInterface;
use Rajtika\SSLCommerz\Services\SSLBuilder;

class SSLCommerz extends SSLBuilder implements SSLCommerzInterface
{
    public function __construct()
    {
        parent::$config = Config::get('sslcommerz');
    }

    public static function setParams( array $params )
    {
        if( !empty( $params ) ) {
            
            $missinParams = array_diff_key(array_flip([
                'tran_id',
                'total_amount',
                'currency',
                'cus_name',
                'cus_email',
                'cus_phone',
                'product_name',
                'product_category',
                'product_profile'
            ]), $params);

            if( $missinParams ) {
                dd( implode(', ', array_keys( $missinParams ) ) . ' is missing in your params.' );
            }

            self::$params = $params;
        }
        return new static;
    }

    public static function setShippingInfo( array $params )
    {
        if( !empty( $params ) ) {
            
            $missinParams = array_diff_key(array_flip([
                'shipping_method',
                'num_of_item'
            ]), $params);

            if( $missinParams ) {
                dd( implode(', ', array_keys( $missinParams ) ) . ' is missing in your params.' );
            }
            $params = array_merge_recursive( self::$params, $params);
            self::$params = $params;
            // dd( self::$params );
        }
         return new static;
    }

    public static function makePayment()
    {
        return parent::_handSheke();
    }

    public static function validateTransaction( $trxID, $amount, $currency, $requestData )
    {
        return 'You are just call to validate transaction';
        self::__handShake();
    }
}