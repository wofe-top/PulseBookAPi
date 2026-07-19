<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';
        if ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
        }


        if ($request->has('lang')) {
            $locale = $request->query('lang');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
