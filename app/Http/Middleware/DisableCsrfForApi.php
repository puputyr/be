<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCsrfForApi
{
    public function handle(Request $request, Closure $next)
    {
        // Jika route dimulai dengan 'api/', kita abaikan CSRF token
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Lakukan validasi CSRF seperti biasa jika tidak di route API
        return app('App\Http\Middleware\VerifyCsrfToken')->handle($request, $next);
    }
}

