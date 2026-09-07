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
        if (str_contains(request()->getHost(), 'ngrok') || request()->secure()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Carbon\Carbon::setLocale('id');
        \Illuminate\Pagination\Paginator::useTailwind();

        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}
