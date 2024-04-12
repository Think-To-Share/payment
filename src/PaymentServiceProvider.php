<?php

declare(strict_types=1);

namespace ThinkToShare\Payment;

use Cashfree\Cashfree;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use ThinkToShare\Payment\Contracts\Gateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/payment.php' => config_path('payment.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'payment');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/payment'),
        ]);

        $this->publishesMigration([__DIR__.'/../database/migrations' => database_path('migrations')],'payment-migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/payment.php', 'payment'
        );

        $this->app->bind(Gateway::class, function (Application $app) {
            return new PaymentManager($app);
        });

        $this->app->bind(Cashfree::class, function (Application $app) {
            $config = $app['config']['payment']['gateways']['cashfree'];

            Cashfree::$XClientId = $config['client_id'];
            Cashfree::$XClientSecret = $config['client_secret_key'];
            Cashfree::$XEnvironment = config('payment.sandbox', false) ? Cashfree::$SANDBOX : Cashfree::$PRODUCTION;

            return new Cashfree();
        });
    }
}
