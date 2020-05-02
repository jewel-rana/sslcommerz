<?php
namespace Rajtika\SSLCommerz\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SSLCommerzFacade
 * @package Rajtika\SSLCommerz
 */
class SSLCommerzFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sslcommerz';
    }
}