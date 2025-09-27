<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [
        'id',
    ];

    public function notifier()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
