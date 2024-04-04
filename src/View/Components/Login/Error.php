<?php

namespace Wjbecker\FilamentConnectify\View\Components\Login;

use Illuminate\View\Component;
use Illuminate\View\View;
use Wjbecker\FilamentConnectify\FilamentConnectify;

class Error extends Component
{
    public function shouldRender(): bool
    {
        return session()->has('filament.connectify.login.error');
    }

    public function render(): View
    {
        return view('filament-connectify::components.login.error', [
            'message' => session()->pull('filament.connectify.login.error')
        ]);
    }
}
