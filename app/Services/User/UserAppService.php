<?php

namespace App\Services\User;

use App\Exceptions\AuthException;
use App\Models\Counter;
use App\Models\User;
use App\Repositories\Eloquent\User\UserAppRepository;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\User\UserAppRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class UserAppService
{
    public function __construct(private CounterRepositoryInterface $repository,
    private UserAppRepositoryInterface $appRepository

    ){}
   public function resetPassword(int $id,string $currentPassword,string $newPassword){
        $user=User::find($id);
        if (!$user){
          throw AuthException::usernotExists();
        }
        if (!Hash::check($currentPassword,$user->password)){
           throw AuthException::invalidCredentials();
        }
        return $this->appRepository->resetPassword($id,$newPassword);
   }
    public function name(int $id,array $name)
    {

        $user=$this->appRepository->name($id,$name);
        return $user;
    }

    public function getCounters(int $id)
    {
        $user=User::find($id);
        if (! $user){
            throw AuthException::usernotExists();
        }
        $user=$this->appRepository->getCounters($id);
        return $user;
    }

    public function consumptionRate(int $id)
    {
        // TODO: Implement consumptionRate() method.
    }

    public function getPayments(int $id ,Request $request)
    {
        return $this->appRepository->getPayments($id,[ 'date' => $request->query('date')]);
    }
    public function getCounter(int $id){
        return $this->appRepository->getCounter($id);
    }
    public function getBoxes(){
        return $this->appRepository->getBoxes();
    }
   public function getConsumption(int $counter_id){


       $now=Carbon::now();
       $current = [
           'daily' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfDay(), $now->copy()->endOfDay()),
           'weekly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfWeek(), $now->copy()->endOfWeek()),
           'monthly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfMonth(), $now->copy()->endOfMonth()),
           'yearly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfYear(), $now->copy()->endOfYear())
       ];
     return [
         'daily' => round($this->calculateDailyAverage($counter_id, $now), 2),
         'weekly' => round($this->calculateWeeklyAverage($counter_id, $now), 2),
         'monthly' => round($this->calculateMonthlyAverage($counter_id, $now), 2),
         'yearly' => round($this->calculateYearlyAverage($counter_id, $now), 2)
       ];
//       return [
////           'current' => $current,
//           'average' => $average,
//           'periods' => [
//               'daily' => $now->toDateString(),
//               'weekly' => $now->copy()->startOfWeek()->toDateString() . ' to ' . $now->copy()->endOfWeek()->toDateString(),
//               'monthly' => $now->format('F Y'),
//               'yearly' => $now->format('Y')
//           ]
//       ];
   }
    private function calculateDailyAverage(int $counter_id, Carbon $date)
    {
        // Average of last 30 days
        $startDate = $date->copy()->subDays(29)->startOfDay();
        $endDate = $date->copy()->endOfDay();

        $totalConsumption = $this->appRepository->getConsumption($counter_id, $startDate, $endDate);

        return $totalConsumption / 30;
    }

    private function calculateWeeklyAverage(int $counter_id, Carbon $date)
    {
        // Average of last 8 weeks
        $startDate = $date->copy()->subWeeks(7)->startOfWeek();
        $endDate = $date->copy()->endOfWeek();

        $totalConsumption = $this->appRepository->getConsumption($counter_id, $startDate, $endDate);

        return $totalConsumption / 8;
    }

    private function calculateMonthlyAverage(int $counter_id, Carbon $date)
    {
        // Average of last 6 months
        $startDate = $date->copy()->subMonths(5)->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        $totalConsumption = $this->appRepository->getConsumption($counter_id, $startDate, $endDate);

        return $totalConsumption / 6;
    }

    private function calculateYearlyAverage(int $counter_id, Carbon $date)
    {
        // Average of last 3 years
        $startDate = $date->copy()->subYears(2)->startOfYear();
        $endDate = $date->copy()->endOfYear();

        $totalConsumption = $this->appRepository->getConsumption($counter_id, $startDate, $endDate);

        return $totalConsumption / 3;
    }



}
