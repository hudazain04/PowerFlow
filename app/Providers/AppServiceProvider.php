<?php

namespace App\Providers;

use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\User\ComplaintRepository;
use App\Repositories\interfaces\AuthRepositoryInterface;

use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Listeners\NotifySuperAdmin;
use App\Listeners\SendApprovalNotification;
use App\Listeners\SendVerificationCode;
use App\Repositories\Eloquent\UserRepository;

use App\Repositories\Eloquent\Admin\PowerGeneratorRepository;
use App\Repositories\Eloquent\AppInfoRepository;
use App\Repositories\Eloquent\SuperAdmin\FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\Plan_FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanPriceRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionRequestRepository;
use App\Repositories\Eloquent\SuperAdmin\VisitorRepository;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\AppInfoRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\VisitorRepositoryInterface;

use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
        $this->app->bind(PlanRepositoryInterface::class,PlanRepository::class);
        $this->app->bind(PlanPriceRepositoryInterface::class,PlanPriceRepository::class);
        $this->app->bind(Plan_FeatureRepositoryInterface::class,Plan_FeatureRepository::class);
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(PowerGeneratorRepositoryInterface::class,PowerGeneratorRepository::class);
        $this->app->bind(SubscriptionRequestRepositoryInterface::class,SubscriptionRequestRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class,SubscriptionRepository::class);
        $this->app->bind(AppInfoRepositoryInterface::class,AppInfoRepository::class);
        $this->app->bind(VisitorRepositoryInterface::class,VisitorRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class,ComplaintRepository::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    protected $listen = [
        UserRegistered::class => [
            SendVerificationCode::class,
            NotifySuperAdmin::class,
        ],
        UserApproved::class => [
            SendApprovalNotification::class,
        ],
    ];

}
