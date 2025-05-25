<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PlanPrice extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
