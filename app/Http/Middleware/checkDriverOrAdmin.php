<?php

namespace App\Http\Middleware;

use App\Http\Library\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkDriverOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role == "admin" || $request->user()->role == "driver") {
            return $next($request);
        }

        return HttpResponse::respondUnAuthenticated();
    }
}
