<?
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Illuminate\Foundation\Events\ServerStarting' => [
            'App\Listeners\RunTransactionsHistory',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}