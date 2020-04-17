<?php
namespace Rajtika\SSLCommerz\Services;
use Rajtika\SSLCommerz\Libs\Validator;

abstract class SSLBuilder
{
    protected static $params;
    protected static $config;

    public static function init()
    {
        if( empty( self::$config ) )
            dd('We could not find the SSLCommerz config items.');

        if( empty( self::$config['credentials']['store_id'] ) 
            || empty( self::$config['credentials']['store_password'] ) ) {
            dd('You haven\'t set store_id or store_password yet');
        }
    }                                    

    protected static function _handSheke()
    {
        self::init();
        self::sanitize();
        $response = self::_apiCall();

        return $response;
    }

    private static function sanitize()
    {
        //if shipping method is YES then check shipping details provided
        if( array_key_exists('shipping_method', self::$params ) 
            && in_array( self::$params['shipping_method'], ['YES', 'Courier']  ) ) {
            $shippingInfo = array_diff_key(array_flip([
                'num_of_item'
            ]), self::$params);

            if( $shippingInfo ) {
                dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
            }
        }

        if( array_key_exists('product_profile', self::$params ) ) {
            if( self::$params['product_profile'] == 'airline-tickets' ) {
                $shippingInfo = array_diff_key(array_flip([
                    'hours_till_departure', 
                    'flight_type', 
                    'pnr', 
                    'journey_from_to', 
                    'third_party_booking', 
                    'hotel_name',
                    'length_of_stay', 
                    'check_in_time',
                ]), self::$params);

                if( $shippingInfo ) {
                    dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
                }
            } elseif( self::$params['product_profile'] == 'travel-vertical' ) {
                $shippingInfo = array_diff_key(array_flip([ 
                    'hotel_name',
                    'length_of_stay', 
                    'check_in_time',
                    'hotel_city'
                ]), self::$params);

                if( $shippingInfo ) {
                    dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
                }
            } elseif( self::$params['product_profile'] == 'telecom-vertical' ) {
                $shippingInfo = array_diff_key(array_flip([ 
                    'product_type',
                    'topup_number', 
                    'country_topup'
                ]), self::$params);

                if( $shippingInfo ) {
                    dd( implode(', ', array_keys( $shippingInfo ) ) . ' is missing in your params.' );
                }
            }
        }

        Validator::validate( self::$params );
    }

    private static function getUrl( $key = 'make_payment' )
    {
        return self::$config['apiDomain'] . self::$config['apiUrl'][$key];
    }

    private static function getHeader()
    {
        return [];
    }

    private static function getBody()
    {
        $additionalInfoMissing = array_diff_key(array_flip([
            'cus_state',
            'cus_city',
            'cus_country',
            'ship_name',
            'ship_add1',
            'ship_city',
            'ship_postcode',
            'ship_country'
        ]), self::$params);

        $additionalInfo = [];
        foreach( $additionalInfoMissing as $k => $v ) {
            $additionalInfo[$k] = "";
        }

        return array_merge_recursive([
            'store_id' => self::$config['credentials']['store_id'],
            'store_passwd' => self::$config['credentials']['store_password'],
            'success_url' => url(self::$config['success_url']),
            'cancel_url' => url(self::$config['cancel_url']),
            'ipn_url' => url(self::$config['ipn_url']),
        ], self::$params, $additionalInfo);
    }

    private static function _apiCall()
    {
        // dd( self::getBody() );
        $curl = curl_init();

        if (self::$config['connect_from_localhost'] === false ) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // The default value for this option is 2. It means, it has to have the same name in the certificate as is in the URL you operate against.
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // When the verify value is 0, the connection succeeds regardless of the names in the certificate.
        }

        curl_setopt($curl, CURLOPT_URL, self::getUrl());
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::getHeader());
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, self::getBody());

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlErrorNo = curl_errno($curl);
        curl_close($curl);

        if( $curlErrorNo )
            $response = json_encode(['status' => 'FAILED', 'failedreason' => "Api connect failed", 'sessionkey' => '', 'gw' => []]);
            //return "cURL Error #:" . $err;
        
        return $response;
    }
}