<?php

namespace Wjbecker\FilamentConnectify\Facades;

use Illuminate\Support\Facades\Facade;

class FilamentConnectify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-connectify';
    }
}
