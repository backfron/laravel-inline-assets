<?php

namespace Backfron\LaravelInlineAssets;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LaravelInlineAssetsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Blade::directive('inlineAsset', function ($expression) {
            return (new LaravelInlineAssets($expression, 'asset'))->render();
        });

        Blade::directive('inlineMix', function ($expression) {
            return (new LaravelInlineAssets($expression, 'mix'))->render();
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-inline-assets.php', 'laravel-inline-assets');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-inline-assets'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-inline-assets.php' => config_path('laravel-inline-assets.php'),
        ], 'laravel-inline-assets.config');
    }
}
