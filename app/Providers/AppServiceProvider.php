<?php

namespace App\Providers;

use App\Support\MailConfig;
use App\Support\PublicSiteData;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
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
        Paginator::useBootstrapFive();
        MailConfig::applyFromDatabase();

        View::composer('*', function ($view) {
            $view->with('publicShell', PublicSiteData::shell());
        });
    }
}
