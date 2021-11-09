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
            $filePath = str_replace(['\'', '"'], '', $expression);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            if (in_array(config('app.env'), config('laravel-inline-assets.inline'))) {
                $inlineContent = addslashes(File::get(public_path($filePath)));

                if ($extension == 'css') {
                    return "<?php echo '<style>{$inlineContent}</style>'; ?>";
                }

                if ($extension == 'js') {
                    return "<?php echo '<script>{$inlineContent}</script>'; ?>";
                }
            }

            if (!in_array(config('app.env'), config('laravel-inline-assets.inline'))) {

                if ($extension == 'css') {
                    return "<?php echo '<link rel=\"stylesheet\" href=\"" . asset($filePath) ."\">'; ?>";
                }

                if ($extension == 'js') {
                    return "<?php echo '<script src=\"" . asset($filePath) . "\"></script>'; ?>";
                }
            }


        });

        Blade::directive('inlineMix', function ($expression) {
            $filePath = str_replace(['\'', '"'], '', $expression);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (in_array(config('app.env'), config('laravel-inline-assets.inline'))) {
                $inlineContent = addslashes(File::get(public_path($filePath)));

                if ($extension == 'css') {
                    return "<?php echo '<style>{$inlineContent}</style>'; ?>";
                }

                if ($extension == 'js') {
                    return "<?php echo '<script>{$inlineContent}</script>'; ?>";
                }
            }

            if (!in_array(config('app.env'), config('laravel-inline-assets.inline'))) {

                $filePath = mix($filePath);

                if ($extension == 'css') {
                    return "<?php echo '<link rel=\"stylesheet\" href=\"" . $filePath . "\">'; ?>";
                }

                if ($extension == 'js') {
                    return "<?php echo '<script src=\"" . $filePath . "\"></script>'; ?>";
                }
            }
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

        // Register the service the package provides.
        $this->app->singleton('laravel-inline-assets', function ($app) {
            return new LaravelInlineAssets;
        });
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
