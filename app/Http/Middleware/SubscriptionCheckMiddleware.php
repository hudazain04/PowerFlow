<?php

namespace App\Http\Middleware;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Models\Subscription as SubscriptionModel;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isEmpty;

class SubscriptionCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        $user =auth()->user();
//        if ($user && $user->hasRole('admin') && $user->powerGenerator) {
//            $generator = $user->powerGenerator;
//            $subscription = SubscriptionModel::where(['generator_id'=>$generator->id,'expired_at'=>null])->get()
//                ->filter(function ($subscription){
//                    return $subscription->start_time->addMonths($subscription->period)->gt(now());
//
//                });
//            if (isEmpty($subscription) || Carbon::now()->greaterThan(
//                    $subscription->start_time->addMonths($subscription->period)
//                )) {
//                throw new ErrorException( __('messages.error.expiredSubscription'),ApiCode::FORBIDDEN, ['subscription_expired' => true]);
//
//            }
//        }

        return $next($request);
    }
}
