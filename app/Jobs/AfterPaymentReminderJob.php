<?php

namespace App\Jobs;

use App\Models\PowerGenerator;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Services\FirebaseService;
use App\Types\SpendingTypes;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class AfterPaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PowerGenerator $generator;

    public function __construct(PowerGenerator $generator,
                                protected CounterRepositoryInterface $counterRepository)
    {
        $this->generator = $generator;
    }

    public function handle(): void
    {
        $counters=$this->counterRepository->get($this->generator->id,[],['spendingType'=>SpendingTypes::After]);
        foreach ($counters as $counter)
        {
            $user=$counter->user;
            if ($user->fcmToken) {
                FirebaseService::sendNotification(
                    $user->fcmToken,
                    "You have to pay , your meter  will be cut",
                    "Your counter due date for payment is tomorrow , you have today and tomorrow before 6 pm",
                    [
                        'counter_id'=>$counter->id,
                    ]
                );
            }
        }
        CutAfterPaymentJob::dispatch($this->generator)->delay(Carbon::tomorrow()->setHour(18)->setMinute(0));

    }
}
