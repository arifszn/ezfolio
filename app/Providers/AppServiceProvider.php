<?php

namespace App\Providers;

use App\Services\AboutService;
use App\Services\AdminService;
use App\Services\Contracts\AboutContract;
use App\Services\Contracts\AdminContract;
use App\Services\Contracts\SettingContract;
use App\Services\SettingService;
use Config;
use Illuminate\Support\ServiceProvider;
use Schema;
use Str;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SettingContract::class, SettingService::class);
        $this->app->bind(AboutContract::class, AboutService::class);
        $this->app->bind(AdminContract::class, AdminService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        
        Schema::defaultStringLength(191);
    }
}
