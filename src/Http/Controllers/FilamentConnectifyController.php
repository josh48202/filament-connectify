<?php

namespace Wjbecker\FilamentConnectify\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User;
use Wjbecker\FilamentConnectify\FilamentConnectifyPlugin;
use Wjbecker\FilamentConnectify\Models\SocialiteUser;

class FilamentConnectifyController extends Controller
{
    private mixed $decodedToken;

    public function __construct()
    {
    }

    public function redirect(string $provider)
    {
        return Socialite::driver($provider)
            ->redirectUrl($this->redirectUrl($provider))
            ->scopes(FilamentConnectifyPlugin::get()->getProviderScopes($provider))
            ->redirect();
    }

    public function callback(string $provider)
    {
        /** @var User $socialite */
        $socialite = Socialite::driver($provider)->stateless()->user();

        if (!$this->isTenantAllowed($socialite)) {
            session()->flash('filament.connectify.login.error', 'Tenant is not allowed.');
            return redirect()->route(FilamentConnectifyPlugin::get()->getLoginRoute());
        }

        $socialiteUser = SocialiteUser::updateOrCreate([
            'provider' => $provider,
            'provider_user_id' => $socialite->getId(),
        ], [
            'name' => $socialite->getName(),
            'email' => $socialite->getEmail(),
            'token' => $socialite->token,
            'refresh_token' => $socialite->refreshToken,
            'expires_at' => match ($provider) {
                'azure' => now()->addSeconds($socialite->expiresIn),
                default => null,
            },
        ]);

        $user = FilamentConnectifyPlugin::get()->getUserModel()::updateOrCreate([
            'email' => $socialiteUser->email,
        ], [
            'name' => $socialiteUser->name
        ]);

        if (!$socialiteUser->user) {
            $socialiteUser->user()->associate($user)->save();
        }

        Auth::login($user);

        return redirect()->route(FilamentConnectifyPlugin::get()->getLoginRoute());
    }

    private function isTenantAllowed($socialiteUser)
    {
        return app()->call(FilamentConnectifyPlugin::get()->getTenantAllowedCallback(), ['socialiteUser' => $socialiteUser]);
    }

    private function redirectUrl($provider)
    {
        return app()->call(FilamentConnectifyPlugin::get()->getRedirectUrlCallback(), ['provider' => $provider]);
    }
}
