<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use Translatable;
    protected $fillable = [
        'user_id',
        'generator_id',
        'status',
        'user_notes',
        'admin_notes'
    ];
    public $translatable=[
        'status',
        'user_notes',
        'admin_notes',
    ];
    public function user(){
        return $this->BelongsTo(User::class);
    }
}
