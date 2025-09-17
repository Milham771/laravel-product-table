<?php

namespace ProductPackage;

use Illuminate\Support\ServiceProvider;
use ProductPackage\Http\Controllers\ProductTableController;
use ProductPackage\Http\Middleware\CorsMiddleware;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config only if the config file exists
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
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/product-package'),
        ], 'product-package-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'product-package');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/product-package.php' => config_path('product-package.php'),
        ], 'product-package-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'product-package-migrations');

        // Register routes
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../src/routes/web.php';
            require __DIR__.'/../src/routes/api.php';
        }

        // Register middleware
        $this->app['router']->aliasMiddleware('product-package.cors', CorsMiddleware::class);
    }
}