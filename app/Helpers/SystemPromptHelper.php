<?php

namespace App\Helpers;
Use App\Models\Embedding;

class SystemPromptHelper
{
    public static function UtterancesIntents(){
    // 🧩 Intents con sus entidades asociadas
        $embeddingsIntents = Embedding::with('intent')->get()->map(function ($embeddings) {
            return "- Expresiones: {$embeddings->content} | Intent: {$embeddings->intent->intent} → id: {$embeddings->intent->id}";
        })->implode("\n");

        // 🎯 Prompt con estructura semántica real
            $system = <<<PROMPT
                Eres un buscador de Expresiones e intents

                🧾 “Usa únicamente las Expresiones e intents registrados en el sistema; no respondas mas nada.”
                "primero, evalua y compara bien todas las expreciones y luego selecciona el intent mas parecido"

                la descripcion de cada Intent describe su funcion, usala como referencia:
                Expresiones e Intents disponibles:
                {$embeddingsIntents}

                y responde solo con el id => id:

                Si no encuentras un intent que coincida, responde '0'
            PROMPT;
        return $system;
    }
}
