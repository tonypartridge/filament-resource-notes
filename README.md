# Plugin for filament resources / models for notes and sub comments, with actions and media

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tonypartridge/filament-notes-action.svg?style=flat-square)](https://packagist.org/packages/tonypartridge/filament-notes-action)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tonypartridge/filament-notes-action/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tonypartridge/filament-notes-action/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/tonypartridge/filament-notes-action/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/tonypartridge/filament-notes-action/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tonypartridge/filament-notes-action.svg?style=flat-square)](https://packagist.org/packages/tonypartridge/filament-notes-action)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require tonypartridge/filament-notes-action
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-notes-action-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-notes-action-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-notes-action-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentNotesAction = new Tonypartridge\FilamentNotesAction();
echo $filamentNotesAction->echoPhrase('Hello, Tonypartridge!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Tony Partridge & Diogo Pinto](https://github.com/tonypartridge)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
