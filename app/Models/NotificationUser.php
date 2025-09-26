<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NotificationUser extends Pivot
{
    protected $guarded = [
        'id',
    ];


    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function notified()
    {
        return $this->morphTo();
    }

}
