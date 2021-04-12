<?php

namespace App\Providers;

use App\Services\AboutService;
use App\Services\AdminService;
use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\AdminInterface;
use App\Services\Contracts\EducationInterface;
use App\Services\Contracts\PortfolioConfigInterface;
use App\Services\Contracts\SettingInterface;
use App\Services\EducationService;
use App\Services\PortfolioConfigService;
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
        $this->app->bind(SettingInterface::class, SettingService::class);
        $this->app->bind(AboutInterface::class, AboutService::class);
        $this->app->bind(AdminInterface::class, AdminService::class);
        $this->app->bind(PortfolioConfigInterface::class, PortfolioConfigService::class);
        $this->app->bind(EducationInterface::class, EducationService::class);
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
