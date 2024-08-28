<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class TransactionHistoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        // if ($this->app->runningInConsole()) {
        //     return;
        // }

        // $this->runTransactionHistoryLoop();
    }
    protected function runTransactionHistoryLoop()
    {
        // while (true) {
        //     // Run the Artisan command
        //     Artisan::call('transactions:get-history');
        //     sleep(60); // 3600 seconds = 1 hour
        // }
    }
}
