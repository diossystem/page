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
          $this->publishes([
              __DIR__.'/../database/migrations/' => database_path('migrations')
          ], 'migrations');
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
