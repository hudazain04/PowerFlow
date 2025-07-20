<?php

use Illuminate\Support\Facades\Broadcast;
Broadcast::routes([
    'middleware' => ['auth:api'],
]);
Broadcast::channel('admin.dashboard', function ($user) {
    \Illuminate\Support\Facades\Log::info("Auth attempt for user {$user->id} " );
    return $user->hasRole('super admin');
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

