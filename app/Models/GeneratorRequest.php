<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GeneratorRequest extends Model
{
    use HasFactory, Notifiable;
    use Translatable;
    protected $fillable=[
        'first_name',
        'last_name',
        'generator_name',
        'generator_location',
        'phone',
        'status',
        'user_id'

    ];
    public $translatable=[
        'generator_name',
        'generator_location',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
