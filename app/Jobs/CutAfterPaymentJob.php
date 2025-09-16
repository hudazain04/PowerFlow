<?php

namespace App\Jobs;

use App\Events\AdminActionEvent;
use App\Models\PowerGenerator;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\GeneratorSettingRepositoryInterface;
use App\Services\Admin\ActionService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;
use App\Types\SpendingTypes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CutAfterPaymentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public PowerGenerator $generator;

    public function __construct(PowerGenerator $generator,
    protected CounterRepositoryInterface $counterRepository,
    protected GeneratorSettingRepositoryInterface $generatorSettingRepository,
    protected ActionService $actionService,
    )
    {
        $this->generator=$generator;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $counters=$this->counterRepository->get(generator_id : $this->generator->id, wheres : ['spendingType'=>SpendingTypes::After]);
        foreach ($counters as $counter)
        {
            $setting=$this->generatorSettingRepository->get($this->generator->id);
            $latestPayment=$this->counterRepository->latestPayment($counter);
            $latestSpending=$this->counterRepository->latestSpending($counter);
            $latestPaymentDate=$latestPayment->date;
            $nextDueDate =$setting->nextDueDate;
            $dayBeforeDue = $nextDueDate->copy()->subDay();

            if (!($latestPaymentDate->isSameDay($nextDueDate) || $latestPaymentDate->isSameDay($dayBeforeDue))) {
                $action=$this->actionService->create([
                    'type'=>ActionTypes::Cut,
                    'status'=>ComplaintStatusTypes::Pending,
                    'generator_id'=>$this->generator->id,
                    'counter_id'=>$counter->id,
                    'relatedData'=>['latestSpending'=>$latestSpending,
                        'latestPayment'=>$latestPayment]

                ]);
                event(AdminActionEvent::dispatch($this->generator->id,$action));

            }

        }
    }
}
