<?php

namespace App\Providers;

use App\Events\EmployeeLocationUpdate;
use App\Listeners\StoreEmployeeLocation;
use App\Models\Spending;
use App\Observers\SpendingObserver;
use App\Repositories\Eloquent\Admin\ActionRepository;
use App\Repositories\Eloquent\Admin\GeneratorSettingRepository;
use App\Repositories\Eloquent\Admin\PaymentRepository;
use App\Repositories\Eloquent\Admin\SpendingRepository;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionPaymentRepository;
use App\Repositories\Eloquent\User\ComplaintRepository;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\GeneratorSettingRepositoryInterface;
use App\Repositories\interfaces\Admin\PaymentRepositoryInterface;
use App\Repositories\interfaces\Admin\SpendingRepositoryInterface;
use App\Repositories\interfaces\AuthRepositoryInterface;

//use App\Events\UserApproved;
//use App\Events\UserRegistered;
//use App\Listeners\NotifySuperAdmin;
//use App\Listeners\SendApprovalNotification;
//use App\Listeners\SendVerificationCode;
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
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;



use App\Repositories\interfaces\SuperAdmin\VisitorRepositoryInterface;

use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
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
        $this->app->bind(SubscriptionPaymentRepositoryInterface::class,SubscriptionPaymentRepository::class);
        $this->app->bind(SpendingRepositoryInterface::class,SpendingRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class,PaymentRepository::class);
        $this->app->bind(ActionRepositoryInterface::class,ActionRepository::class);
        $this->app->bind(GeneratorSettingRepositoryInterface::class,GeneratorSettingRepository::class);



    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Spending::observe(SpendingObserver::class);
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                // Add HTTP Bearer security scheme
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Event::listen(
            EmployeeLocationUpdate::class,
            StoreEmployeeLocation::class,
        );
    }
//    protected $listen = [
//        UserRegistered::class => [
//            SendVerificationCode::class,
//            NotifySuperAdmin::class,
//        ],
//        UserApproved::class => [
//            SendApprovalNotification::class,
//        ],
//    ];

}
