<?php

namespace Wjbecker\FilamentConnectify\View\Components\Login;

use Illuminate\View\Component;
use Illuminate\View\View;
use Wjbecker\FilamentConnectify\FilamentConnectify;
use Wjbecker\FilamentConnectify\FilamentConnectifyPlugin;

class Providers extends Component
{
    public function __construct(protected FilamentConnectify $package)
    {
    }

    public function render(): View
    {
        return view('filament-connectify::components.login.providers', [
            'providers' => FilamentConnectifyPlugin::get()->getProviders(),
            'route' => FilamentConnectifyPlugin::get()->getRedirectRoute(),
        ]);
    }
}
