<?php

namespace App\Providers;

use App\Notifications\VerifyEmailNotification;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\SuperAdmin\GeneratorRequestRepository;
use App\Repositories\Eloquent\User\PasswordResetRepository;
use App\Repositories\Eloquent\User\VerificationRepository;
use App\Repositories\interfaces\FaqRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
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
