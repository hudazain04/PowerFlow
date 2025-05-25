<?php

namespace App\Providers;

use App\Notifications\VerifyEmailNotification;
use App\Repositories\Eloquent\Admin\AreaRepository;
use App\Repositories\Eloquent\Admin\CounterRepository;
use App\Repositories\Eloquent\Admin\ElectricalBoxRepository;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\SuperAdmin\GeneratorRequestRepository;
use App\Repositories\Eloquent\SuperAdmin\NeighborhoodRepository;
use App\Repositories\Eloquent\User\CustomerRequestRepository;
use App\Repositories\Eloquent\User\PasswordResetRepository;
use App\Repositories\Eloquent\User\VerificationRepository;
use App\Repositories\interfaces\Admin\AreaRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use App\Repositories\interfaces\FaqRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\NeighborhoodRepositoryInterface;
use App\Repositories\interfaces\User\CustomerRequestRepositoryInterface;
use App\Repositories\interfaces\User\PasswordResetRepositoryInterface;
use App\Repositories\interfaces\User\VerificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
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
