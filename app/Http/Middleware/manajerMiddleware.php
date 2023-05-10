<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use auth;

class manajerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('manajer_api')->user()->role=="manajer") {
            return $next($request);
        } else {
            $message = ["message"=>'Permission Denied'];
            return response()->json($message);
        }
        return $next($request);
    }
}
