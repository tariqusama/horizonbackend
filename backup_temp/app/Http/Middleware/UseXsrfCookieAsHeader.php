<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cross-origin SPAs (e.g. *.vercel.app → horizon.co3.solutions) cannot read
 * XSRF-TOKEN via document.cookie, so axios never sends X-XSRF-TOKEN.
 * The browser still sends the cookie with withCredentials — copy it to the header.
 */
class UseXsrfCookieAsHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && ! $request->header('X-XSRF-TOKEN')
            && $request->cookie('XSRF-TOKEN')
        ) {
            $request->headers->set('X-XSRF-TOKEN', $request->cookie('XSRF-TOKEN'));
        }

        return $next($request);
    }
}
