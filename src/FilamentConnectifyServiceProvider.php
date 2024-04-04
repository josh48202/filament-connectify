<?php

namespace Wjbecker\FilamentConnectify;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentConnectifyServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-connectify')
            ->hasViews()
            ->hasMigrations(['create_socialite_users_table', 'make_password_nullable_on_users_table'])
            ->hasRoute('web');
    }

    public function packageRegistered(): void
    {
        $this->app->alias(FilamentConnectify::class, 'filament-connectify');
    }
}
