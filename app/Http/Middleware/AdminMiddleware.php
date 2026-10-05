<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Verifica que el usuario esté autenticado y tenga rol de administrador (RBAC).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Saioa hasi behar duzu administrazio atalean sartzeko (Debes iniciar sesión para acceder al panel de administración).');
        }

        if (!Auth::user()->isAdmin()) {
            // OWASP A09: Security Logging & Monitoring
            Log::warning('Segurtasun abisua / Security Alert: Atentado de acceso no autorizado a ruta administrativa.', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent()
            ]);

            abort(403, 'Ez duzu baimenik orri honetan sartzeko (Acceso denegado: Se requieren permisos de administrador).');
        }

        return $next($request);
    }
}
