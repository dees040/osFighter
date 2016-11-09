<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('App\Library\Game', function ($app) {
            return new \App\Library\Game();
        });

        $this->app->singleton('App\Library\UserHandler', function ($app) {
            return new \App\Library\UserHandler();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
