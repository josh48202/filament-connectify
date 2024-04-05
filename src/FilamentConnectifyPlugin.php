<?php

namespace Wjbecker\FilamentConnectify;

use App\Models\User;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Blade;

class FilamentConnectifyPlugin implements Plugin
{
    protected array $providers = [];

    protected string $redirectRoute;

    protected string $loginRoute;

    protected string $userModel = User::class;

    protected ?Closure $redirectUrlCallback;

    protected ?Closure $isAllowedCallback;

    private string $callbackRoute;


    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-connectify';
    }

    public function register(Panel $panel): void
    {
        $this->redirectRoute = 'filament.connectify.'.$panel->getId().'.redirect';
        $this->loginRoute = 'filament.'.$panel->getId().'.auth.login';
        $this->callbackRoute = 'filament.connectify.'.$panel->getId().'.callback';

        $panel->renderHook('panels::auth.login.form.before', function () {
            return Blade::render('<x-filament-connectify::login.error />');
        });

        $panel->renderHook('panels::auth.login.form.after', function () {
            return Blade::render('<x-filament-connectify::login.providers />');
        });
    }

    public function boot(Panel $panel): void
    {
        Blade::componentNamespace('Wjbecker\\FilamentConnectify\\View\\Components', 'filament-connectify');
    }

    public function providers(array $providers): static
    {
        $this->providers = $providers;

        return $this;
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());
        return $plugin;
    }

    public function getCallbackRoute(): string
    {
        return $this->callbackRoute;
    }

    public function getLoginRoute(): string
    {
        return $this->loginRoute;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getProviderScopes($provider): array
    {
        return $this->providers[$provider]['scopes'] ?? [];
    }

    public function getRedirectRoute(): string
    {
        return $this->redirectRoute;
    }

    public function getRedirectUrlCallback(): Closure
    {
        return $this->redirectUrlCallback ?? function ($provider) {
            return route(FilamentConnectifyPlugin::get()->getCallbackRoute(), [$provider]);
        };
    }

    public function getIsAllowedCallback(): Closure
    {
        return $this->isAllowedCallback ?? function ($socialiteUser) {
            return true;
        };
    }

    public function getUserModel(): string
    {
        return $this->userModel;
    }

    public function redirectUrlCallback(Closure $callback = null): static
    {
        $this->redirectUrlCallback = $callback;

        return $this;
    }

    public function isAllowedCallback(Closure $callback = null): static
    {
        $this->isAllowedCallback = $callback;

        return $this;
    }

    public function userModel(string $model): static
    {
        if (!is_subclass_of($model, Authenticatable::class, true)) {
            throw new \Exception('User model class must implement Authenticatable interface.');
        }

        $this->userModel = $model;

        return $this;
    }
}
