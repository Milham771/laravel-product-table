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
        if (file_exists(__DIR__.'/../config/product-package.php')) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/product-package.php', 'product-package'
            );
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/product-package'),
        ], 'product-package-views');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'product-package');

        $this->publishes([
            __DIR__.'/../config/product-package.php' => config_path('product-package.php'),
        ], 'product-package-config');

        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'product-package-migrations');

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes/web.php';
            
            $this->app['router']->group(['prefix' => 'api'], function () {
                require __DIR__.'/routes/api.php';
            });
        }
    }
}