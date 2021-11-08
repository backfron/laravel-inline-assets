<?php

namespace Backfron\LaravelInlineAssets\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelInlineAssets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-inline-assets';
    }
}
