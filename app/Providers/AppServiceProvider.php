<?php

namespace App\Providers;

use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Listeners\NotifySuperAdmin;
use App\Listeners\SendApprovalNotification;
use App\Listeners\SendVerificationCode;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
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
