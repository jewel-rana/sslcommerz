<?php
namespace Rajtika\SSLCommerz\Libs;
use Illuminate\Support\Facades\Redirect;

class SSLHandler {
	protected static $response;
	protected static $method;
	protected static $type;

	public static function hosted()
	{
		Header("Location: " . self::merchantUrl(), true, 307);
		exit('If you seed this message, then you didn\'t redirect to the merchant page');
	}

	public static function checkout()
	{
		return self::parseResponse();
	}

	private static function merchantUrl()
	{
		extract( self::parseArray() );
		if( $status == 'SUCCESS' ) {
			return $redirectGatewayURL;
		}
	}

	private static function parseResponse()
	{
		return json_decode( self::$response );
	}

	private static function parseArray()
	{
		$object = json_decode( self::$response );
		return json_decode(json_encode($object), true);
	}
}