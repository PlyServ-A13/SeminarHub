<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; // Cette ligne manquait
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class FortifyServiceProvider extends ServiceProvider
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
        // Liaison pour la réponse de connexion
        $this->app->singleton(
            LoginResponseContract::class,
            LoginResponse::class
        );

        // Liaison pour la réponse d'inscription
        $this->app->singleton(
            RegisterResponseContract::class,
            RegisterResponse::class
        );

        // Désactiver le rate limiting directement
        RateLimiter::for('login', function () {
            return Limit::none();
        });

        RateLimiter::for('two-factor', function () {
            return Limit::none();
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        
    }
}