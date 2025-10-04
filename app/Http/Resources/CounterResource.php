<?php

namespace App\Http\Resources;

use App\Models\Subscription as SubscriptionModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\isEmpty;

class CounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $generator = $this->powerGenerator;
        $subscription = SubscriptionModel::where(['generator_id'=>$generator->id,'expired_at'=>null])->get()
            ->filter(function ($subscription){
                return ($subscription->start_time)->addMonths($subscription->period)->gt(now());

            });
        $blocked=$generator->user->blocked;

        return [
            'id' => $this->id,
            'number' => $this->number,
            'QRCode' => $this->QRCode,
            'status' => $this->status,
            'current_spending' => $this->current_spending,
            'spendingType' => $this->spendingType,
            'physical_device_id' => $this->physical_device_id,
            'box_id' => $this->electricalBoxes->first() ? $this->electricalBoxes->first()->id : null,
            'user' => UserResource::make($this->user),
            'blocked'=>(isEmpty($subscription)|| $blocked)
        ];
    }
}
