<?php

namespace App\Http\Middleware;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::user();
//        dd($user->blocked);
        if ($user->blocked)
        {
            return $this->success(null,__('auth.blocked'),ApiCode::FORBIDDEN);
        }
        return $next($request);
    }
}
