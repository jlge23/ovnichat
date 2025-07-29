<?php

namespace App\Traits;

trait UsesOllamaOptions
{
    public function ollamaOptions(): array
    {
        return [
            'temperature' => 0.0,        // menor aleatoriedad, más enfoque
            'top_p' => 0.95,              // flexibilidad controlada en la selección de tokens
            'repeat_penalty' => 1.1,      // evita respuestas repetitivas
            'presence_penalty' => 0.0,    // permite mantener contexto sin forzar novedad
            'frequency_penalty' => 0.0,   // neutral ante palabras comunes
            'num_predict' => 150          // suficiente para una respuesta clara y completa
        ];
    }
}
