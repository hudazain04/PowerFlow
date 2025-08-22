<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Phone extends Model
{
    use HasFactory;
    protected $fillable = [
          'number',
        'generator_id'
    ];
    public function powerGenerator(){
        return $this->BelongsTo(PowerGenerator::class);
    }
}
