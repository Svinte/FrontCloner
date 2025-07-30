<?php

namespace Svinte\FrontCloner;

use FrontCloneCache;
use Illuminate\Support\ServiceProvider;
use Vendor\FrontCloner\Helpers\UrlHelper;

class FrontClonerServiceProvider extends ServiceProvider
{
    /**
     * Register service.
     */
    public function register()
    {
        // Services
        $this->app->singleton(CloneService::class, function($app) {
            return new CloneService();
        });

        // Helpers
        $this->app->singleton(UrlHelper::class, function($app) {
            return new UrlHelper();
        });

        // Cache
        $this->app->singleton(FrontCloneCache::class, function($app) {
            return new FrontCloneCache();
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
