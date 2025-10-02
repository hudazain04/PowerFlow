<?php

namespace App\Http\Middleware;

use App\Models\Subscription as SubscriptionModel;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user =auth()->user();
        if ($user && $user->hasRole('admin') && $user->powerGenerator) {
            $generator = $user->powerGenerator;
            $subscription = SubscriptionModel::where(['generator_id'=>$generator->id,'expired_at'=>null])->get()
                ->filter(function ($subscription){
                    return $subscription->start_time->addMonths($subscription->period)->gt(now());

                });
            if (!$subscription || Carbon::now()->greaterThan(
                    $subscription->start_time->addMonths($subscription->period)
                )) {
                return response()->json([
                    'message' => __('messages.error.expiredSubscription'),
                    'subscription_expired' => true
                ], 403);
            }
        }

        return $next($request);
    }
}
