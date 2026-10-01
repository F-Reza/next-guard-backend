<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME-type sniffing
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Prevent clickjacking
        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        // Control referrer information
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        // Disable unnecessary browser capabilities
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // Basic legacy XSS protection header
        $response->headers->set(
            'X-XSS-Protection',
            '0'
        );

        // HSTS only when the request is actually HTTPS.
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}