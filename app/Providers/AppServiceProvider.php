<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // 🛡️ Mahasiswa hanya boleh lolos jika kolom role bernilai 'student'
    \Illuminate\Support\Facades\Gate::define('access-student', function ($user) {
        return $user->role === 'mahasiswa';
    });

    // 🛡️ Merchant hanya boleh lolos jika kolom role bernilai 'merchant'
    \Illuminate\Support\Facades\Gate::define('access-merchant', function ($user) {
        return $user->role === 'merchant';
    });
}
}
