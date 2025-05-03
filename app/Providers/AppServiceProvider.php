<?php

namespace App\Providers;

use App\Repositories\Eloquent\SuperAdmin\FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\Plan_FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanPriceRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanRepository;
use App\Repositories\interfaces\SuperAdmin\FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(PlanRepositoryInterface::class,PlanRepository::class);
        $this->app->bind(PlanPriceRepositoryInterface::class,PlanPriceRepository::class);
        $this->app->bind(Plan_FeatureRepositoryInterface::class,Plan_FeatureRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
