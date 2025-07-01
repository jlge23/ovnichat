<?php

namespace App\Traits;

trait UsesOllamaOptions
{
    public function ollamaOptions(): array
    {
        return [
            'temperature' => 0.7,
            'top_p' => 0.9,
            'repeat_penalty' => 1.2,
            'presence_penalty' => 0.4,
            'frequency_penalty' => 0.4,
            'num_predict' => 250,
            'num_thread' => 2,
            'stop' => ['Human:', 'Assistant:', '<|end_of_turn|>'],
            'seed' => null,
        ];
    }
}
