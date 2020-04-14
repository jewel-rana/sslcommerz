<?php
namespace Rajtika\SSLCommerz;

use Illuminate\Support\ServiceProvider;
use Rajtika\SSLCommerz\Services\SSLCommerz;

class SSLCommerzServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sslcommerz', function () {
            return new SSLCommerz();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/sslcommerz.php' =>  config_path('sslcommerz.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
}
