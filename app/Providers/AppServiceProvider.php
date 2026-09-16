<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

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
        // Ensure sidebar always receives role-based menu data through the composer.
        View::composer('layouts.components.sidebar-roles', MenuComposer::class);

        if (in_array(env('APP_ENV'), ['production', 'ministry', 'egov'])) {
            URL::forceRootUrl(Config::get('app.url'));
            URL::forceScheme('https');
        }
    }
}
