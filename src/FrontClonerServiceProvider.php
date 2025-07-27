<?php

namespace Vendor\FrontCloner;

use Illuminate\Support\ServiceProvider;
use Vendor\FrontCloner\Helpers\UrlHelper;

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

        $this->app->singleton(UrlHelper::class, function($app) {
            return new UrlHelper();
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
