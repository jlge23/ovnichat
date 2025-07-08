<?php

namespace App\Traits;

use App\Models\Intent;
use App\Models\Entitie;
use Illuminate\Support\Facades\Log;

trait UsesSystemsOptions
{
    public function construirSystemPrompt(): string
    {
        $intents = Intent::with(['entities', 'businessModels'])
        ->whereHas('businessModels', function ($query) {
            $query->where('business_models.id', 9);
        })
        ->get()
        ->map(function ($intent) {
            return [
                'nombre' => $intent->name,
                'descripcion' => $intent->description,
                'entidades' => $intent->entities->pluck('name')->toArray(),
                'ModeloNegocio' => $intent->businessModels->pluck('name'),
            ];
        });
        Log::info($intents->pluck('ModeloNegocio'));

        return <<< PROMPT
            Eres un analizador semántico de . Detecta si el mensaje corresponde a alguno de los intents y entities definidos y extraer las entidades relevantes.

            📌 Si detectas un intent válido, responde exclusivamente con un JSON como este:

            {
            "intent": "disponibilidad_producto",
            "entities": {
                "nombre_producto": "arroz",
                "cantidad": 2,
                "presentacion": "funda",
                "peso_presentacion":"2 kilos",
                "marca": "Favorita"
                }
            }

            ❌ Si no identificas ningún intent válido o el mensaje no coincide con ninguno, responde: NO ENTENDI NADA
            📋 Intents y Entities disponibles:
            {$intents->toJson(JSON_PRETTY_PRINT)}
        PROMPT;

    }
}
