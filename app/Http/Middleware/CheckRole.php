<?php

namespace App\Http\Middleware;

use App\Services\SecurityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'No autenticado.'], 401)
                : redirect()->route('home');
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // Log de seguridad: acceso denegado por falta de rol.
        app(SecurityLogger::class)->record(
            'access_denied',
            'Acceso denegado a '.$request->method().' /'.ltrim($request->path(), '/').' (requiere: '.implode(', ', $roles).').',
            $request->user()->getAuthIdentifier(),
            $request->user()->email ?? null,
        );

        return $request->expectsJson()
            ? response()->json(['message' => 'No tienes permiso para realizar esta accion.'], 403)
            : abort(403, 'No tienes permiso para realizar esta accion.');
    }
}
