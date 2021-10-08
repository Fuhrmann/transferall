<?php

namespace App\Providers;

use App\Contracts\TransactionAuthorizerContract;
use App\Notifications\Channels\PayServiceChannel;
use App\Services\Gateway\DefaultTransactionAuthorizer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransactionAuthorizerContract::class, DefaultTransactionAuthorizer::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_TIME, 'pt-br');

        if (config('app.force_https')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        // Specify our custom notification channel
        Notification::extend('payservice', function ($app) {
            return new PayServiceChannel();
        });
    }
}
