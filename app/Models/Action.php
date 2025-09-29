<?php

namespace App\Models;

use App\Types\ActionTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory;
    protected $guarded=['id'];
    protected $casts=[
      'relatedData'=>'array',
    ];

    public function parent()
    {
        return $this->belongsTo(Action::class,'parent_id');
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class,'counter_id');
    }

    protected static function booted()
    {
        static::creating(function ($action) {
            if (is_array($action->type)) {
                Log::error('Action type is array: ', $action->type);
            }
            $selfPriority = ActionTypes::getPriority($action->type);

            if ($action->parent_id) {
                $parent = Action::find($action->parent_id);
                if ($parent) {
                    $action->priority = max($selfPriority, $parent->priority);
                } else {
                    $action->priority = $selfPriority;
                }
            } else {
                $action->priority = $selfPriority;
            }
        });
    }

}
