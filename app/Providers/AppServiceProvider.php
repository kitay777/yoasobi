<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Features;
use Laravel\Fortify\Features as FortifyFeatures;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::forceScheme('https');

        Inertia::share([
            'jetstream' => [
                'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                'hasEmailVerification' => FortifyFeatures::enabled(FortifyFeatures::emailVerification()),
            ],
        ]);
    }
}
