<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation as UpdatesUserProfileInformationContract;
use App\Actions\Fortify\UpdateUserProfileInformation as ConcreteUpdateAction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            UpdatesUserProfileInformationContract::class,
            ConcreteUpdateAction::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
