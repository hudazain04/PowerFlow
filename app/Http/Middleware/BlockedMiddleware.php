<?php

namespace App\Http\Middleware;

use App\ApiHelper\ApiCode;
use App\Exceptions\ErrorException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user=$request->user();
            if ($user && $user->blocked)
            {
                throw new ErrorException(__('messages.error.blocked_account'),ApiCode::FORBIDDEN);
            }
        }
        catch (\Exception $exception)
        {
            throw new ErrorException(__('messages.error.server_error'),ApiCode::INTERNAL_SERVER_ERROR);
        }
        return $next($request);
    }
}
