<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Translatable;
    protected $fillable = [
       'question',
        'answer',
        'role'
    ];
    public $translatable=[
        'question',
        'answer',
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
