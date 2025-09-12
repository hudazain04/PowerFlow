<?php

namespace App\Models;

use App\Types\ActionTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory;
    protected $guarded=['id'];
    protected $casts=[
      'relatedData'=>'array',
    ];

    protected static function booted()
    {
        static::creating(function ($action) {
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
