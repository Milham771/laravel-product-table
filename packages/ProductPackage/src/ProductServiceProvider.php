<?php

namespace ProductPackage;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'product-package');
        
        // Register routes
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes/web.php';
            require __DIR__.'/routes/api.php';
        }
    }
}