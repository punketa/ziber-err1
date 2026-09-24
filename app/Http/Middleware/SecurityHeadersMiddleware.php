<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     * Inyecta cabeceras HTTP de seguridad (OWASP A05: Security Misconfiguration)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Previene ataques de Clickjacking embebiendo la web en iframes externos
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Previene ataques de MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Filtro XSS para navegadores antiguos
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control de información sensible en cabecera Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restricción de permisos del navegador (Hardware y Geolocation)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy (CSP) permitiendo Bootstrap 5 CDN y recursos propios
        $csp = "default-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "font-src 'self' https://cdn.jsdelivr.net data:; " .
               "img-src 'self' data: https:;";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
