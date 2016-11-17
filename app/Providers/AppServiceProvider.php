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

        $this->app->singleton('dynamic_router', function ($app) {
            return new \App\Library\Routing\DynamicRouter();
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
