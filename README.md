# sslcommerz
SSLCommerz payment gateway integration

[![Latest Stable Version](https://poser.pugx.org/rajtika/sslcommerz/v/stable)](https://packagist.org/packages/rajtika/sslcommerz)

This package will make your integration to SSLCommerz payment gateway simple and easy

## Contents

- [Installation](#installation)
	- [Publish Configuration](#publish-configuration)
	- [Setup and Configure](#setup-and-configure)
- [Usage](#usage)
    - [Make Payment](#make-payment)
    - [Refund Process](#refund-process)
    - [Transaction Query](#transaction-query)
- [Available Methods](#available-methods)
- [Changelog](#changelog)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
    composer require rajtika/sslcommerz
```

### Publish Configuration

Publish configuration file

```bash
    php artisan vendor:publish
```

### Setup and configure

Update your app environment (.env) 
```
    SSLC_STORE_ID           =   [YOUR SSLCOMMERZ STORE_ID]
    SSLC_STORE_PASSWORD     =   [YOUR SSLCOMMERZ STORE_ID]
    SSLC_STORE_CURRENCY     =   [STORE CURRENCY eg. BDT]
    SSLC_ROUTE_SUCCESS      =   [route name of success_url, eg: payment.success]
    SSLC_ROUTE_FAILURE      =   [eg: payment.failure]
    SSLC_ROUTE_CANCE        =   [eg: payment.cancel]
    SSLC_ROUTE_IPN          =   [eg: payment.ipn]
    SSLC_ALLOW_LOCALHOST    =   [TRUE/FALSE]
```
**NOTE** SSLC_ROUTE_* variables are route name() not url()

Create four ``POST`` routes for SSLCommerz
```php
    Route::post('sslcommerz/success','PaymentController@success')->name('payment.success');
    Route::post('sslcommerz/failure','PaymentController@failure')->name('payment.failure');
    Route::post('sslcommerz/cancel','PaymentController@cancel')->name('payment.cancel');
    Route::post('sslcommerz/ipn','PaymentController@ipn')->name('payment.ipn');
```
**NOTE** These named routes are being used in .env file

Add exception in ``app\Http\Middleware\VerifyCsrfToken.php`` 
```php
    protected $except = [
        'sslcommerz/*'
    ];
```
**NOTE** This will be the initial group of those four routes

After done configuraing
```bash
    php artisan config:cache
```

## Usage

### Make Payment
Now you can call for payment from Route or Controller method:

***Route Way***

``` php
SSLCommerz::setParams([
    'tran_id' => 'your_unique_transaction_id',
    'product_name' => 'Name of your product',
    'product_category' => 'Product category',
    'product_profile' => 'general',
    'total_amount' => 100,
    'currency' => 'BDT',
    'cus_name' => 'John Doe', 
    'cus_email' => 'customer@example.com',
    'cus_phone' => '01911XXXXXX',
    'cus_add1' => 'Dhaka'
]) //Shipping is required when your order need shipment
->setShippingInfo([
    'shipping_method' => "YES",
    'num_of_item' => 2
])
->makePayment()
->hosted(); //this method will redirect your customer to ssl commerz payment page
//or
->checkout(); //this method will return a json response for your checkout popup 
```
***Controller Way***
``` php
use Rajtika\SSLCommerz\SSLCommerz;

class PaymentController extends Controller
{
    public function hostedWay()
    {
        ...
        //  DO YOU ORDER SAVING PROCESS TO DB OR ANYTHING
        ...

        // You can generate an unique transaction id by using uniqueid()

        $transaction_id = uniqid(); //this transaction id must save your order or payment table for referencing / validate payment status

        return SSLCommerz::setParams([
            'tran_id' => $transaction_id,
            'product_name' => 'Name of your product',
            'product_category' => 'Product category',
            'product_profile' => 'general',
            'total_amount' => 100,
            'currency' => 'BDT',
            'cus_name' => 'John Doe', 
            'cus_email' => 'customer@example.com',
            'cus_phone' => '01911XXXXXX',
            'cus_add1' => 'Dhaka'
        ]) //Shipping is required when your order need shipment
        ->setShippingInfo([
            'shipping_method' => "YES",
            'num_of_item' => 2
        ])
        ->makePayment()
        ->hosted(); //this method will redirect your customer to ssl commerz payment page
        //or
        ->checkout(); //this method will return a json response for your checkout popup 
    }
}
```
**NOTE** This is the minimalist basic need to perform a payment.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.