<?php

namespace LeeMangold\CreateType;

use Illuminate\Support\ServiceProvider;

class CreateTypeServiceProvider extends ServiceProvider
{



    protected $commands = [
        'LeeMangold\CreateType\Console\Commands\createType'
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'leemangold');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'leemangold');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');


        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/createtype.php', 'createtype');

        // Register the service the package provides.
        $this->app->singleton('createtype', function ($app) {
            return new CreateType;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['createtype'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/createtype.php' => config_path('createtype.php'),
        ], 'createtype.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/leemangold'),
        ], 'createtype.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/leemangold'),
        ], 'createtype.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/leemangold'),
        ], 'createtype.views');*/

        // Registering package commands.
        // $this->commands([]);
        $this->commands($this->commands);

    }
}
