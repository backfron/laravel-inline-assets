<?php

namespace Backfron\LaravelInlineAssets;

use Illuminate\Support\Facades\File;

class LaravelInlineAssets
{
    protected $extension;

    protected $filePath;

    protected $expression;

    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->prepareProperties();
    }

    public function build()
    {
        if ($this->shouldRender()) {

            return $this->buildInlineContent();
        }

        if (!$this->shouldRender()) {

            return $this->buildTag();
        }

    }

    protected function prepareProperties()
    {
        $this->filePath = str_replace(['\'', '"'], '', $this->expression);
        $this->extension = pathinfo($this->filePath, PATHINFO_EXTENSION);
    }

    protected function shouldRender()
    {
        return in_array(config('app.env'), config('laravel-inline-assets.inline'));
    }

    protected function buildInlineContent()
    {
        $inlineContent = addslashes(File::get(public_path($this->filePath)));

        if ($this->extension == 'css') {
            return "<?php echo '<style>{$inlineContent}</style>'; ?>";
        }

        if ($this->extension == 'js') {
            return "<?php echo '<script>{$inlineContent}</script>'; ?>";
        }
    }

    protected function buildTag()
    {
        if ($this->extension == 'css') {
            return "<?php echo '<link rel=\"stylesheet\" href=\"" . asset($this->filePath) . "\">'; ?>";
        }

        if ($this->extension == 'js') {
            return "<?php echo '<script src=\"" . asset($this->filePath) . "\"></script>'; ?>";
        }
    }
}
