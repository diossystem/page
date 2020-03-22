<?php

namespace Dios\System\Page;

use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'diossystem-page');

        $this->publishes([
            __DIR__.'/../database/migrations/base' => database_path('migrations')
        ], 'page-migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/multipurpose' => database_path('migrations')
        ], 'multipurpose-columns-of-page-migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/types' => database_path('migrations')
        ], 'entity-types-of-page-migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
    }
}
