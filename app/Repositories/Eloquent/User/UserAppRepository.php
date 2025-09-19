<?php

namespace App\Repositories\Eloquent\User;

use App\Exceptions\AuthException;
use App\Models\Counter;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\User;
use App\Repositories\interfaces\User\UserAppRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAppRepository implements UserAppRepositoryInterface
{

    public function resetPassword(int $id,string $newPassword)
    {
        $user=User::findOrFail($id);
        $user->password=Hash::make($newPassword);
        $user->save();
        return $user;
    }



    public function name(int $id,array $name)
    {
        $user=User::find($id);
        $user->update($name);
        $user->save();
        return $user;
    }

    public function getCounters(int $id)
    {
       $user=Counter::where('user_id',$id)->get();
       return $user;
    }

    public function consumptionRate(int $id)
    {
        // TODO: Implement consumptionRate() method.
    }

    public function getPayments(int $id,?array $filters = [])
    {
     $payment=Payment::filter($filters)->where('counter_id',$id);
       return $payment;
    }

    public function getCounter(int $id)
    {
        $counter=Counter::find($id);
        return $counter;
    }
    public function getConsumption(int $counter_id, $startDate, $endDate)
    {
        $consumption=Spending::where('counter_id',$counter_id)
        ->whereBetween('date',[$startDate,$endDate])
        ->sum('consume');
        return $consumption;
    }
}
