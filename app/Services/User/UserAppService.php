<?php

namespace App\Services\User;

use App\Exceptions\AuthException;
use App\Models\Counter;
use App\Models\User;
use App\Repositories\Eloquent\User\UserAppRepository;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\User\UserAppRepositoryInterface;
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

    public function getPayments(int $id)
    {
        return $this->appRepository->getPayments($id);
    }
    public function getCounter(int $id){
        return $this->appRepository->getCounter($id);
    }
   public function getConsumption(int $counter_id){
       $now=now();
        return [
            'daily' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfDay(), $now->copy()->endOfDay()),
            'weekly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfWeek(), $now->copy()->endOfWeek()),
            'monthly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfMonth(), $now->copy()->endOfMonth()),
            'yearly' => $this->appRepository->getConsumption($counter_id, $now->copy()->startOfYear(), $now->copy()->endOfYear())

        ];
   }



}
