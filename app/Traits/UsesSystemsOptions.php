<?php

namespace App\Traits;

use App\Models\Intent;
use App\Models\Entitie;

trait UsesSystemsOptions
{
    public function construirSystemPrompt(): string
    {
        $intents = Intent::with('entities')->get()->map(function ($intent) {
            return [
                'nombre' => $intent->name,
                'descripcion' => $intent->description,
                'entidades' => $intent->entities->pluck('name')->toArray()
            ];
        });

        $entities = Entitie::all()->mapWithKeys(function ($entitie) {
            return [$entitie->name => $entitie->description];
        });

        return <<<PROMPT
            Eres un analizador semántico de mensajes de clientes. Tu tarea es detectar si el mensaje corresponde a alguno de los intents definidos y extraer las entidades relevantes.

            📌 Si detectas un intent válido, responde exclusivamente con un JSON como este:

            {
            "intent": "disponibilidad_producto",
            "entities": {
                "nombre_producto": "arroz",
                "cantidad": 2,
                "presentacion": "funda",
                "peso_presentacion": "2 kilos",
                "marca": "Favorita"
            }
            }

            ❌ Si no identificas ningún intent válido o el mensaje no coincide con ninguno, responde en lenguaje natural como si fueras un asistente conversacional amable.

            📋 Intents disponibles:
            {$intents->toJson(JSON_PRETTY_PRINT)}

            📦 Entities disponibles:
            {$entities->toJson(JSON_PRETTY_PRINT)}
        PROMPT;

    }
}
