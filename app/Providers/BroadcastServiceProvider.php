<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;


class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register broadcast routes with the correct middleware for JWT
        Broadcast::routes(['middleware' => ['auth:api']]);

        // Load your channel definitions
        require base_path('routes/channels.php');
    }
}
