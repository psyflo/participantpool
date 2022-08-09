<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Do not run sanctum migratons, otherwise table "personal_access_tokens" is always created
         */
        Sanctum::ignoreMigrations();

        /*
         * Fix schema in production envirnoment or hasValidSignature() method at email verification will fail
         */
        if (config('app.env') === 'production')
        {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * See https://laravel-news.com/laravel-5-4-key-too-long-error
         */
        Schema::defaultStringLength(191);

        /*
         * Fix schema problem behind nginx proxy in production environment
         */
        if (config('app.env') === 'production')
        {
            URL::forceScheme('https');
        }

        /*
         * Superadmin gate interception
         * 
         * If the before closure returns a non-null result that result will be considered the result of the authorization check.
         */
        Gate::before(function ($user, $ability)
        {
            /*
             * Return true to override every authorization check for superadmin, else do not return anything to continue
             */
            if ($user->id === 1)
            {
                return true;
            }
        });
    }
}
