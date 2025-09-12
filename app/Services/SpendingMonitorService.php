<?php
namespace App\Services;

use App\Events\AdminActionEvent;
use App\Http\Resources\CounterResource;
use App\Models\Action;
use App\Models\Counter;
use App\Models\Spending;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\interfaces\Admin\ActionRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Services\Admin\EmployeeAssignmentService;
use App\Types\ActionTypes;
use App\Types\ComplaintStatusTypes;

class SpendingMonitorService
{
    public function __construct(
        protected CounterRepositoryInterface $counterRepository,
        protected ActionRepositoryInterface $actionRepository,
        protected EmployeeAssignmentService $employeeAssignmentService,
    )
    {
    }

    public function checkSpending(Counter $counter)
    {
        $latestPayment=$this->counterRepository->latestPayment($counter);

        $latestSpending = $this->counterRepository->latestSpending($counter);

        if (!$latestSpending || empty($latestSpending->next_spending) || $latestSpending->next_spending == 0) {
            return;
        }

        $percentage = ($latestSpending->consume / $latestSpending->next_spending) * 100;
        $percentage = ($latestSpending->consume / $latestSpending->next_spending) * 100;

        if ($percentage >= 90) {
            $action=$this->actionRepository->create([
                'type'=> ActionTypes::Cut,
                'status'=>ComplaintStatusTypes::Pending,
                'counter_id'=>$counter->id,
                'generator_id'=>$counter->generator_id,
                'relatedData'=>['latestSpending'=>$latestSpending,'latestPayment'=>$latestPayment],
            ]);
            event(new AdminActionEvent($counter->generator_id, $action));

        }
        elseif ($percentage >= 75) {
            $user=$counter->user;
            if ($user->fcmToken) {
                FirebaseService::sendNotification(
                    $user->fcmToken,
                    "You have to pay , your meter  will be cut",
                    "You have consumed 75% of your next spending",
                    [
                        'counter_id'=>$counter->id,
                        'latestSpending'=>$latestSpending,
                        'latestPayment'=>$latestPayment,

                    ]
                );
            }
        }
    }

}
