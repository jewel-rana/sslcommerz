# sslcommerz
SSLCommerz payment gateway integration

[![Latest Stable Version](https://poser.pugx.org/rajtika/sslcommerz/v/stable)](https://packagist.org/packages/rajtika/sslcommerz)

This package will make your integration to SSLCommerz payment gateway simple and easy

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
SSL_LOCALHOST=[TRUE/FALSE]
SSL_SANDBOX_MODE=[TRUE/FALSE]
SSL_STORE_ID=[YOUR SSLCOMMERZ STORE ID]
SSL_STORE_PASSWORD=[YOUR SSLCOMMERZ STORE_PASSWORD]
SSL_SUCCESS_URL=sslcommerz/success
SSL_CANCEL_URL=sslcommerz/cancel
SSL_FAIL_URL=sslcommerz/fail
SSL_IPN_URL=sslcommerz/ipn
SSL_STORE_CURRENCY=[STORE CURRENCY eg. BDT]
```

Create four ``POST`` routes for SSLCommerz
```php
    Route::post('sslcommerz/success','SSLPaymentController@success');
    Route::post('sslcommerz/fail','SSLPaymentController@fail');
    Route::post('sslcommerz/cancel','SSLPaymentController@cancel');
    Route::post('sslcommerz/ipn','SSLPaymentController@ipn');
```
**NOTE** These routes are being used in .env file

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
Route::post('make-payment', function() {
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
});
```
***Controller Way***
``` php
use Rajtika\SSLCommerz\SSLCommerz;

class PaymentController extends Controller
{
    public function paymentRedirectWay()
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
    }
    //or
    public function popupWay()
    {
        ...
        //  DO YOU ORDER SAVING PROCESS TO DB OR ANYTHING
        ...

        // You can generate an unique transaction id by using uniqueid()

        /*
        * this transaction id must save your order or payment table 
        * for referencing / validate payment status
        * You can make this more unique by passing prefix to it
        */
        $transaction_id = uniqid(); 

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
        ]) 
        //Shipping is required when your order need shipment
        ->setShippingInfo([
            'shipping_method' => "YES",
            'num_of_item' => 2
        ])
        ->makePayment()
        ->checkout(); //this method will return a json response for your checkout popup 
    }
}
```
**NOTE** This is the minimalist basic need to perform a payment.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.