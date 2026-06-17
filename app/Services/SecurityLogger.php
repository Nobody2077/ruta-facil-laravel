<?php

namespace App\Services;

use App\Models\SecurityLog;

class SecurityLogger
{
    /**
     * Registra un evento de seguridad capturando IP y user-agent de la petición actual.
     */
    public function record(string $event, ?string $description = null, ?int $userId = null, ?string $email = null): SecurityLog
    {
        $request = request();

        return SecurityLog::create([
            'user_id'     => $userId,
            'event'       => $event,
            'description' => $description,
            'email'       => $email,
            'ip_address'  => $request?->ip(),
            'user_agent'  => $request ? substr((string) $request->userAgent(), 0, 255) : null,
        ]);
    }
}
