<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
