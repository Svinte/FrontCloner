<?php

namespace Vendor\FrontCloner;

use Illuminate\Support\ServiceProvider;

class FrontClonerServiceProvider extends ServiceProvider
{
    /**
     * Register service.
     */
    public function register()
    {
        // CloneService
        $this->app->singleton(CloneService::class, function($app) {
            return new CloneService();
        });

        // Config
        $this->mergeConfigFrom(__DIR__.'/../config/frontcloner.php', 'frontcloner');
    }

    /**
     * Boot service.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/frontcloner.php' => config_path('frontcloner.php'),
        ], 'config');
    }
}
