<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SecureInputIAHelper
{
    public static function entradaSegura(string $msg): bool
    {
        // ⚠️ Lista de comandos sospechosos
        $bloqueados = [
            '\\restart-ai', '\\inject', '/system', '/debug', '/shutdown',
            'role: system', '"role": "system"', 'reset context', '#prompt','/restart','/restart-ai','/restart-all-ais',
            'system:', 'clear memory', 'reconfigure','restart-ai','restart-ai-aio','systemprompt','system-prompt','system prompt'
        ];

        foreach ($bloqueados as $patron) {
            if (Str::contains(Str::lower($msg), Str::lower($patron))) {
                return false;
            }
        }

        // ⚠️ Comienza con algún comando especial
        if (preg_match('/^(\\\\|\/)([a-z0-9\-_]+)$/i', $msg)) {
            return false;
        }

        return true;
    }

    public static function sanitizarMensaje(string $msg): string
    {
        return Str::replaceMatches('/[^\p{L}\p{N}\s\.,!?@:\-áéíóúÁÉÍÓÚñÑ]/u', '', $msg);
    }
}
