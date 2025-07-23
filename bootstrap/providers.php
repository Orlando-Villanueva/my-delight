<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\PerformanceServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TelescopeServiceProvider;

$providers = [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    PerformanceServiceProvider::class,
    RouteServiceProvider::class,
];

// Only register Telescope in local environment
if (app()->environment('local')) {
    $providers[] = TelescopeServiceProvider::class;
}

return $providers;
