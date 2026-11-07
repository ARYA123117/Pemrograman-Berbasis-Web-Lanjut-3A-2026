<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // SOAL 3: Definisi Gate kelola-buku (Hanya izinkan Admin dan Petugas)
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('petugas');
        });
    }
}