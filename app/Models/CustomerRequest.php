<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    protected $fillable = [
        'user_id',
        'generator_id',
        'status',
        'user_notes',
        'admin_notes'
    ];
    public function user(){
        return $this->BelongsTo(User::class);
    }
}
