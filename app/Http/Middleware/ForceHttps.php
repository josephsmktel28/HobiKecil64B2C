<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if in production and not already on HTTPS
        if (config('app.env') === 'production') {
            // Check multiple headers for HTTPS indication from proxy/load balancer
            $isSecure = $request->secure() ||
                        $request->header('X-Forwarded-Proto') === 'https' ||
                        $request->header('HTTP_X_FORWARDED_PROTO') === 'https';

            if (!$isSecure) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
