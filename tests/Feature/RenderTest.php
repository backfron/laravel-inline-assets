<?php

namespace Backfron\LaravelInlineAssets\Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Backfron\LaravelInlineAssets\Tests\LaravelInlineAssetsTestCase;

class RenderTest extends LaravelInlineAssetsTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Config::set('laravel-inline-assets.inline', ['testing']);
        $this->resetAppFileSystem();
    }

    public function resetAppFileSystem()
    {
        if (is_file(resource_path('views/app.blade.php'))) {
            unlink(resource_path('views/app.blade.php'));
        }

        if (is_file(public_path('mix-manifest.json'))) {
            unlink(public_path('mix-manifest.json'));
        }

        if (is_file(public_path('hot'))) {
            unlink(public_path('hot'));
        }

        if (is_dir(public_path('css'))) {
            File::deleteDirectory(public_path('css'));
        }

        if (is_dir(public_path('js'))) {
            File::deleteDirectory(public_path('js'));
        }
    }

    public function inlineAssetHtml()
    {
        $strView = <<<VIEW
        <!DOCTYPE html>
        <html lang="en">
        <head>
            @inlineAsset('css/app.css')
        </head>
        <body>
            @inlineAsset('js/app.js')
        </body>
        </html>
        VIEW;
        $this->prepareAssets($strView);

        return $strView;
    }

    public function prepareAssets($view)
    {
        File::put(resource_path('views/app.blade.php'), $view);

        if (!is_dir(public_path('css'))) {
            File::makeDirectory(public_path('css'));
        }
        File::put(public_path('css/app.css'), "body{color:blue;}");

        if (!is_dir(public_path('js'))) {
            File::makeDirectory(public_path('js'));
        }
        File::put(public_path('js/app.js'), "var foo = 'bar';");
    }

    public function inlineMixHtml()
    {
        $strView = <<<VIEW
        <!DOCTYPE html>
        <html lang="en">
        <head>
            @inlineMix('css/app.css')
        </head>
        <body>
            @inlineMix('js/app.js')
        </body>
        </html>
        VIEW;
        $this->prepareAssets($strView);
        $this->prepareManifest($strView);

        return $strView;
    }

    protected function prepareManifest()
    {
        $manifest = <<<MANIFEST
        {
            "/js/app.js": "/js/app.js?id=eb825c7f030fcb8e9650",
            "/css/app.css": "/css/app.css?id=68b329da9893e34099c7"
        }

        MANIFEST;
        File::put(public_path('mix-manifest.json'), $manifest);
    }

    /** @test */
    public function render_file_contents_in_specified_evironments()
    {
        $this->inlineAssetHtml();

        $renderedView = view('app')->render();

        $this->assertStringContainsString('<style>body{color:blue;}</style>', $renderedView);
        $this->assertStringContainsString('<script>var foo = \'bar\';</script>', $renderedView);
    }

    /** @test */
    public function render_file_tags_in_no_specified_evironments()
    {
        Config::set('laravel-inline-assets.inline', ['production']);

        $this->inlineAssetHtml();

        $renderedView = view('app')->render();

        $this->assertStringContainsString('<link rel="stylesheet" href="http://assets.test/css/app.css">', $renderedView);
        $this->assertStringContainsString('<script src="http://assets.test/js/app.js"></script>', $renderedView);
    }

    /** @test */
    public function render_file_contents_in_specified_evironments_using_inlineMix()
    {
        $this->inlineMixHtml();

        $renderedView = view('app')->render();

        $this->assertStringContainsString('<style>body{color:blue;}</style>', $renderedView);
        $this->assertStringContainsString('<script>var foo = \'bar\';</script>', $renderedView);
    }

    /** @test */
    public function render_file_tags_if_HMR_is_running_inlineMix()
    {
        Config::set('laravel-inline-assets.inline', ['production']);

        $this->inlineMixHtml();
        File::put(public_path('hot'), 'http://localhost:8080');

        $renderedView = view('app')->render();

        $this->assertStringContainsString('<link rel="stylesheet" href="//localhost:8080/css/app.css">', $renderedView);
        $this->assertStringContainsString('<script src="//localhost:8080/js/app.js"></script>', $renderedView);

    }

    /** @test */
    public function render_file_tags_if_HMR_is_not_running_with_inlineMix_in_non_production_env()
    {
        Config::set('laravel-inline-assets.inline', ['local']);

        $this->inlineMixHtml();

        $renderedView = view('app')->render();

        $this->assertStringContainsString('<link rel="stylesheet" href="/css/app.css?id=68b329da9893e34099c7">', $renderedView);
        $this->assertStringContainsString('<script src="/js/app.js?id=eb825c7f030fcb8e9650"></script>', $renderedView);
    }

}

