<?php

namespace App\Providers;

use App\Http\Controllers\Admin\CounterBoxController;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\Eloquent\Admin\AreaRepository;
use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Repositories\Eloquent\Admin\CounterRepository;
use App\Repositories\Eloquent\Admin\ElectricalBoxRepository;
use App\Repositories\Eloquent\Admin\EmployeeRepository;
use App\Repositories\Eloquent\Admin\PowerGeneratorRepository;
use App\Repositories\Eloquent\AppInfoRepository;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\SuperAdmin\FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\GeneratorRequestRepository;
use App\Repositories\Eloquent\SuperAdmin\NeighborhoodRepository;
use App\Repositories\Eloquent\SuperAdmin\Plan_FeatureRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanPriceRepository;
use App\Repositories\Eloquent\SuperAdmin\PlanRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionPaymentRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionRepository;
use App\Repositories\Eloquent\SuperAdmin\SubscriptionRequestRepository;
use App\Repositories\Eloquent\SuperAdmin\VisitorRepository;
use App\Repositories\Eloquent\User\ComplaintRepository;
use App\Repositories\Eloquent\User\CustomerRequestRepository;
use App\Repositories\Eloquent\User\PasswordResetRepository;
use App\Repositories\Eloquent\User\UserAppRepository;
use App\Repositories\Eloquent\User\VerificationRepository;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use App\Repositories\interfaces\Admin\EmployeeRepositoryInterface;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\Admin\StatisticsRepositoryInterface;
use App\Repositories\interfaces\AppInfoRepositoryInterface;
use App\Repositories\interfaces\FaqRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\NeighborhoodRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\Plan_FeatureRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanPriceRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionPaymentRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRequestRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\VisitorRepositoryInterface;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use App\Repositories\interfaces\User\CustomerRequestRepositoryInterface;
use App\Repositories\interfaces\User\PasswordResetRepositoryInterface;
use App\Repositories\interfaces\User\UserAppRepositoryInterface;
use App\Repositories\interfaces\User\VerificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    protected $policies = [
        \App\Models\Area::class => \App\Policies\AreaPolicy::class,
        \App\Models\ElectricalBox::class => \App\Policies\ElectricalBoxPolicy::class,
        \App\Models\Counter::class => \App\Policies\CounterPolicy::class,
        \App\Models\Employee::class => \App\Policies\EmployeePolicy::class,
    ];

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FaqRepositoryInterface::class,FaqRepository::class);
        $this->app->bind(VerificationRepositoryInterface::class,VerificationRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class,PasswordResetRepository::class);
        $this->app->bind(GeneratorRequestRepositoryInterface::class,GeneratorRequestRepository::class);
        $this->app->bind(CustomerRequestRepositoryInterface::class,CustomerRequestRepository::class);
        $this->app->bind(CounterRepositoryInterface::class,CounterRepository::class);
        $this->app->bind(NeighborhoodRepositoryInterface::class,NeighborhoodRepository::class);
        $this->app->bind(AreaRepositoryInterface::class,AreaRepository::class);
        $this->app->bind(ElectricalBoxRepositoryInterface::class,ElectricalBoxRepository::class);
        $this->app->bind(CounterBoxRepositoryInterface::class,CounterBoxRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class,EmployeeRepository::class);
       $this->app->bind(\App\Repositories\interfaces\Employee\EmployeeRepositoryInterface::class,\App\Repositories\Eloquent\Employee\EmployeeRepository::class);
        $this->app->bind(UserAppRepositoryInterface::class,UserAppRepository::class);
        $this->app->bind(StatisticsRepositoryInterface::class,StatisticsRepository::class);
    }

    public function boot()
    {



        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable) {
            return (new VerifyEmailNotification($notifiable))->toMail($notifiable);
        });
    }
    /**
     * Bootstrap services.
     */
//    public function boot(): void
//    {
//        //
//    }
}
