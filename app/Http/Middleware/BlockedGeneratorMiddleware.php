<?php

namespace App\Http\Middleware;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use App\Models\PowerGenerator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockedGeneratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('employee')->check()) {
            $employee = Auth::guard('employee')->user();

            if ($employee->generator && $employee->generator->is_blocked) {
               throw new ErrorException(__('messages.error.blocked_generator'),ApiCode::FORBIDDEN);
            }

            return $next($request);
        }

        // 🔒 Check if user
        $user = Auth::user();
        if ($user) {
            $generator = $this->getUserGenerator($user->id);
            if ($generator && $generator->user->blocked) {
                throw new ErrorException(__('messages.error.blocked_generator'),ApiCode::FORBIDDEN);
            }
        }
        return $next($request);
    }

    private function getUserGenerator($userId)
    {
        return PowerGenerator::whereHas('areas.areaBoxes.box.counterBoxes.counter', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->first();
    }
}
