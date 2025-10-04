<?php

namespace App\Jobs;

use App\Models\PowerGenerator;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Services\FirebaseService;
use App\Services\NotificationService;
use App\Types\NotificationTypes;
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

    public function __construct(PowerGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function handle(CounterRepositoryInterface $counterRepository ,NotificationService $notificationService): void
    {
        $counters=$counterRepository->get($this->generator->id,[],['spendingType'=>SpendingTypes::After]);
        foreach ($counters as $counter)
        {
            $user=$counter->user;
            $notificationService->notifyCustomUser([
                'title'=>__('notification.pay'),
                'body'=> __('notification.payAfter'),
                'type'=>NotificationTypes::CustomUser,
                'ids'=>[$user->id],
            ]);

        }
        CutAfterPaymentJob::dispatch($this->generator)->delay(Carbon::tomorrow()->setHour(18)->setMinute(0));

    }
}
