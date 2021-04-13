<?php

namespace App\Providers;

use App\Models\Experience;
use App\Services\AboutService;
use App\Services\AdminService;
use App\Services\Contracts\AboutInterface;
use App\Services\Contracts\AdminInterface;
use App\Services\Contracts\EducationInterface;
use App\Services\Contracts\ExperienceInterface;
use App\Services\Contracts\PortfolioConfigInterface;
use App\Services\Contracts\SettingInterface;
use App\Services\Contracts\SkillInterface;
use App\Services\EducationService;
use App\Services\ExperienceService;
use App\Services\PortfolioConfigService;
use App\Services\SettingService;
use App\Services\SkillService;
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
        $this->app->bind(ExperienceInterface::class, ExperienceService::class);
        $this->app->bind(SkillInterface::class, SkillService::class);
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
