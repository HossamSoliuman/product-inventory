<?php

namespace App\Providers;

use App\Events\StockThresholdReached;
use App\Listeners\NotifyLowStock;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Paginator::useBootstrap();
        Event::listen(StockThresholdReached::class, NotifyLowStock::class);
    }
}
