<?php

namespace DigitalTunnel\HyperPay\Providers;

use DigitalTunnel\HyperPay\Services\HyperPay;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register a class in the service container
        $this->app->bind('hyperpay', function () {
            return new HyperPay();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'../config/hyperpay.php' => config_path('hyperpay.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('hyperpay', 'DigitalTunnel\\HyperPay\\Facades\\HyperPay');
    }
}
