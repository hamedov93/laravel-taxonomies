<?php

namespace Hamedov\Taxonomies;

use Illuminate\Support\ServiceProvider;


class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/taxonomies.php', 'taxonomies');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/taxonomies.php' => config_path('taxonomies.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
