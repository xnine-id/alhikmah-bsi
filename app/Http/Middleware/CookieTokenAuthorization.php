<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieTokenAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('Authorization') && $request->cookie(config('sanctum.ac_key'))) {
            $token = $request->cookie(config('sanctum.ac_key'));
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}