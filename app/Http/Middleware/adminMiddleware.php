<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use auth;

class adminMiddleware
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
        if (auth('admin_api')->user()->role=="admin") {
            return $next($request);
        } else {
            $message = ["message"=>'You are not an admin'];
            return response()->json($message);
        }
        // return $next($request);
    }
}
