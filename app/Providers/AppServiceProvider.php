<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(TransactionHistoryProvider::class, function ($app) {
        //      new TransactionHistoryProvider();
        // });
        // if ($this->app->isLocal()) {
        //     Artisan::call('transactions:get-history');
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // "serve-history": [
        //     "php artisan serve",
        //     "php artisan transactions:get-history"
        // ]
        // if ($this->app->isLocal()) {
        //     Artisan::call('transactions:get-history');
        // }
    }
}
