<?php

namespace App\Helpers;

use Carbon\Carbon;

class SaludoHelper
{
    public static function saludoDelDia(string $nombre = 'amigo(a)'): string
    {
        $hora = (int) Carbon::now()->format('H');

        return match (true) {
            $hora >= 0 && $hora < 5  => "☀️ *Buenas madrugadas {$nombre}*! Soy LoSobrinosBot, ¿cómo podemos ayudarte hoy?",
            $hora >= 5 && $hora < 12  => "☀️ *Buenos días {$nombre}*! Soy LoSobrinosBot, ¿cómo podemos ayudarte hoy?",
            $hora >= 12 && $hora < 18 => "🌇 *Buenas tardes {$nombre}*! Soy LoSobrinosBot, ¿cómo podemos ayudarte hoy?",
            default                   => "🌙 *Buenas noches {$nombre}*! Soy LoSobrinosBot, ¿cómo podemos ayudarte hoy?.",
        };
    }
}
