<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneratorRequest extends Model
{
    use HasFactory, Notifiable;

    protected $fillable=[
        'first_name',
        'last_name',
        'generator_name',
        'generator_location',
        'phone',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
