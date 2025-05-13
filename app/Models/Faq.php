<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
       'question',
        'answer',
        'role'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function scopeForCategory($query, $role)
    {
        return $query->where('role', $role);

    }

}
