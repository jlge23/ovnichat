<?php

namespace App;
use Illuminate\Support\Facades\Http;

trait WitAiTrait
{
    public function detectarIntencion($mensaje)
    {
        $respuesta = Http::withToken(config('services.witai.token'))
            ->get(config('services.witai.url'), [
                'q' => $mensaje,
            ]);

        $data = $respuesta->json();

        return [
            'intencion' => $data['intents'][0]['name'] ?? 'desconocido',
            'confianza' => $data['intents'][0]['confidence'] ?? 0,
            'entidades' => $data['entities'] ?? [],
        ];
    }
}
