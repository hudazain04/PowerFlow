<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Neighborhood extends Model
{
    use HasFactory;
    use Translatable;

    protected $fillable = [
        'name',
        'location'
    ];
    public $translatable=[
        'name',
        'location'
    ];
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

}
