<?php

namespace Backfron\LaravelInlineAssets;


class LaravelInlineAssets
{
    protected $extension;

    protected $filePath;

    protected $expression;

    protected $mode;

    public function __construct($expression, $mode)
    {
        $this->expression = $expression;
        $this->mode = $mode;
        $this->prepareProperties();
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->extension == 'css') {
            return $this->buildStyleTag($this->filePath);
        }

        if ($this->extension == 'js') {
            return $this->buildScriptTag($this->filePath);
        }

    }

    /**
     * @return void
     */
    protected function prepareProperties()
    {
        $this->filePath = str_replace(['\'', '"'], '', $this->expression);
        $this->extension = pathinfo($this->filePath, PATHINFO_EXTENSION);
    }

    /**
     * @return boolean
     */
    public static function shouldRender()
    {
        return in_array(config('app.env'), config('laravel-inline-assets.inline'));
    }

    protected function buildStyleTag($path)
    {
        return "
        <?php
        if(!\Backfron\LaravelInlineAssets\LaravelInlineAssets::shouldRender()) {
            echo '<link rel=\"stylesheet\" href=\"' . ('{$this->mode}' == 'asset' ? asset('{$path}') : mix('{$path}')) . '\">';
        }

        if(\Backfron\LaravelInlineAssets\LaravelInlineAssets::shouldRender()) {
            echo '<style>' . \Illuminate\Support\Facades\File::get(public_path('$path')) . '</style>';
        }
        ?>
        ";

    }

    /**
     * @param string $path
     * @return string
     */
    protected function buildScriptTag($path)
    {
        return "
        <?php
        if(!\Backfron\LaravelInlineAssets\LaravelInlineAssets::shouldRender()) {
            echo '<script src=\"' . ('{$this->mode}' == 'asset' ? asset('{$path}') : mix('{$path}')) . '\"></script>';
        }

        if(\Backfron\LaravelInlineAssets\LaravelInlineAssets::shouldRender()) {
            echo '<script>' . \Illuminate\Support\Facades\File::get(public_path('$path')) . '</script>';
        }
        ?>
        ";
    }
}
