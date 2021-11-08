<?php

namespace Backfron\LaravelInlineAssets\Tests;

use Orchestra\Testbench\TestCase;
use Backfron\LaravelInlineAssets\LaravelInlineAssetsServiceProvider;

abstract class LaravelInlineAssetsTestCase extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            LaravelInlineAssetsServiceProvider::class,
        ];
    }
}
