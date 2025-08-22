<?php

namespace App\Models;

use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;

class AppInfo extends Model
{
    use Translatable;
    protected $guarded=['id'];

    public $translatable=[
      'type',
      'content',
    ];
}
