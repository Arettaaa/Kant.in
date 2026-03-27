<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum; 
use App\Models\PersonalAccessToken; 
use Illuminate\Support\Facades\URL; // 1. Tambahkan import ini di atas

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Settingan Sanctum kamu yang lama tetap biarkan ada
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        if (!app()->isLocal()) { // Atau pakai URL::forceScheme('https') saja langsung boleh
            URL::forceScheme('https');
        }
    }
}
