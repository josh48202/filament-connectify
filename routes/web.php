<?php

use Filament\Facades\Filament;
use Wjbecker\FilamentConnectify\Http\Controllers\FilamentConnectifyController;

foreach (Filament::getPanels() as $panel) {
    if (!$panel->hasPlugin('filament-connectify')) continue;

    $domains = $panel->getDomains();
    foreach ((empty($domains) ? [null] : $domains) as $domain) {
        Route::domain($domain)
            ->middleware($panel->getMiddleware())
            ->name('filament.connectify.' . $panel->getId())
            ->prefix($panel->getPath())
            ->group(function () {
                Route::get('/login/{provider}', [FilamentConnectifyController::class, 'redirect'])->name('.redirect');
                Route::get('/login/{provider}/callback', [FilamentConnectifyController::class, 'callback'])->name('.callback');
            });
    }
}
