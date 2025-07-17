<?php

namespace App\Traits;

trait UsesOllamaOptions
{
    public function ollamaOptions(): array
    {
        return [
            'temperature' => 0.0,
            'top_p' => 0.95,
            'temperature' => 0.2,
            'repeat_penalty' => 1.1,
            'presence_penalty' => 0.0,
            'frequency_penalty' => 0.0,
            'num_predict' => 150, // suficiente para un saludo bonito
        ];
    }
}
