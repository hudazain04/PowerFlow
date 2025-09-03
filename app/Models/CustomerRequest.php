<?php

namespace App\Models;

use App\ApiHelper\HasFeatureLimit;
use App\ApiHelper\Translatable;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use Translatable,HasFeatureLimit;
    protected $guarded=['id'];
    public $translatable=[
        'status',
        'user_notes',
        'admin_notes',
    ];
    public  string $featureKey = 'users_count';

    public function user(){
        return $this->BelongsTo(User::class);
    }
    public function box()
    {
        return $this->belongsTo(ElectricalBox::class);
    }

    public function powerGenerator()
    {
        return $this->belongsTo(PowerGenerator::class,'generator_id');
    }
}
