<?php

namespace Arniro\Sluggable;

use Illuminate\Support\ServiceProvider;

class SluggableServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sluggable.php', 'sluggable');
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/sluggable.php' => config_path('sluggable.php'),
        ], 'sluggable.config');
    }
}
