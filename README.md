# Filament Connectify - Social Login through Laravel Socialite

### Add OAuth2 Login support to Filament v3 through Laravel Socialite

This package extends [Laravel Socialite](https://laravel.com/docs/master/socialite). Socialite currently supports
authentication via Facebook, Twitter, LinkedIn, Google, GitHub, GitLab, and Bitbucket out of the box.

Refer to the [Socialite documentation](https://laravel.com/docs/master/socialite) for more information on how to
configure your application to use these providers.

Many other providers are available via the [Socialite Providers](https://socialiteproviders.com/) website. Refer to the
documentation for each provider for information on how to configure your application to use them.

---

## Installation

Install package via composer:
```bash
composer require wjbecker/filament-connectify
```

Publish & migrate migration files
```bash
php artisan vendor:publish --tag="filament-connectify-migrations
php artisan migrate
```

To use provider icons you can add [Blade Font Awesome](https://github.com/owenvoke/blade-fontawesome) brand icons
```bash
composer require owenvoke/blade-fontawesome
```

---

## Provider Configuration

Refer to the [Socialite documentation](https://laravel.com/docs/master/socialite) for more information.

---

## Panel Configuration

Include this plugin in your panel configuration:

```php
use Wjbecker\FilamentConnectify\FilamentConnectifyPlugin;

return $panel
    // ...
    ->plugins([
        // ... Other Plugins
        FilamentConnectifyPlugin::make()
            // (required) add providers
            ->providers([
                'azure' => [
                    'label' => 'Continue with Microsoft',
                    'icon' => 'fab-microsoft', // requires additional package
                ]
            ])
            // (optional) restrict login callback
            ->isAllowedCallback(function (\SocialiteProviders\Manager\OAuth2\User $socialiteUser) {
                $decodedToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $socialiteUser->token)[1]))));
                return $decodedToken->tid === {{azure_tenant_id}};
            })
            // (optional) change the user model class
            ->userModel(\App\Models\User::class)
            // (optional) change redirect url callback
            ->redirectUrlCallback(function ($provider) {
                return 'https://'.tenant('id').'.foo.test'.route(FilamentConnectifyPlugin::get()->getCallbackRoute(), $provider, false);
            })
    ])
```

---

### Sample Provider Configuration - Azure Active Directory

To start, You would refer to the documentation for
the [Azure Socialite Provider](https://socialiteproviders.com/Microsoft-Azure/).

Normally, you would follow the providers documentation on the aforementioned link but to demonstrate, I'll include the steps here.

Per their documentation, you would install the community Azure provider via

```bash
composer require socialiteproviders/microsoft-azure
```

Then you would configure your `config/services.php` file to include the Azure provider's credentials:

```php
'azure' => [    
  'client_id' => env('AZURE_CLIENT_ID'),
  'client_secret' => env('AZURE_CLIENT_SECRET'),
  'redirect' => env('AZURE_REDIRECT_URI'),
  'tenant' => env('AZURE_TENANT_ID'),
  'proxy' => env('PROXY')  // optionally
],
```

In addition, you need to add this provider's event listener to your `app/Providers/EventServiceProvider.php` file:

```php
protected $listen = [
    // ... other listeners
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\Azure\AzureExtendSocialite::class.'@handle',
    ],
];
```

Finally, don't forget to add the needed environment variables to your `.env` file:

```dotenv
AZURE_CLIENT_ID=
AZURE_CLIENT_SECRET=
AZURE_REDIRECT_URI=
AZURE_TENANT_ID=
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
