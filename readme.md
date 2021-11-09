# LaravelInlineAssets

`backfron/laravel-finder` allows you to render your JS and CSS assets inline with the rest of your HTML in production environments. This can improve the load speed of your website reducing the http requests. In fact, I wrote these package in order to improve the results of my webpages in Google [PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/).

## Installation

Via Composer

``` bash
$ composer require backfron/laravel-inline-assets
```

## Usage
Simply replace the use of ```asset()``` and ```mix()``` helpers in your blade files for the directives that this package provides.

```php
// Instead of this
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/app.js') }}"></script>

// Write this
@inlineAsset('css/app.css')
@inlineAsset('js/app.js')
```
And if you are using the ```mix()``` helper:

```php
// Instead of this
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/app.js') }}"></script>

// Write this
@inlineMix('css/app.css')
@inlineMix('js/app.js')
```

The package will automatically creates a link or script tag if you are in non production environment (like local, devlopment, etc.). If you are in production the package gets the file contents and render it inside inline with the HTML using the appropiate tag (```<style>``` or ```<script>```).

If you want to customize the environments in which the asssets should be rendered inline, just publish the config file.

```bash
php artisan vendor:publish --tag=laravel-inline-assets.config
```

Next, add the environments that you want inside the *inline* key.

```php
return [
    'inline' => [
        'production',
        'pre-production',
        'other-env',
        ... // ANY
        ... // OTHER
        ... // ENVIRONMENT
    ],
];
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
phpunit
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Jairo Ushiña][link-author]
- [All Contributors][link-contributors]

## License

Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/backfron/laravel-inline-assets.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backfron/laravel-inline-assets.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/backfron/laravel-inline-assets/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/backfron/laravel-inline-assets
[link-downloads]: https://packagist.org/packages/backfron/laravel-inline-assets
[link-travis]: https://travis-ci.org/backfron/laravel-inline-assets
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/backfron
[link-contributors]: ../../contributors
