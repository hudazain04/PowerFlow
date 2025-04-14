<?php

namespace App\Providers;

use App\Domain\Feature\Repositories\FeatureRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\FeatureRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
