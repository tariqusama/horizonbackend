<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vercel (*.vercel.app) cannot rely on laravel_session cross-origin.
 * Login sets a horizon_token cookie; map it to Authorization for Sanctum.
 */
class AttachBearerFromCookie
{
    public const COOKIE = 'horizon_token';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->bearerToken()) {
            $token = $request->cookie(self::COOKIE);

            if (is_string($token) && $token !== '') {
                $request->headers->set('Authorization', 'Bearer '.$token);
            }
        }

        return $next($request);
    }
}
