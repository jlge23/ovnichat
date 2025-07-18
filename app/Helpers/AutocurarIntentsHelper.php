<?php

namespace App\Helpers;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Helpers\SystemPromptHelper;

class AutocurarIntentsHelper
{
    public static function autocurar($texto)
    {
        $options = [
            'temperature' => 0,           // 🔒 Baja aleatoriedad, evita creatividad excesiva
            'top_p' => 1.0,               // 🔒 Mantiene cobertura completa sin limitar tokens
            'repeat_penalty' => 1.1,      // Penaliza redundancia moderadamente
            'presence_penalty' => 1,      // Evita inventar nuevas ideas ausentes
            'frequency_penalty' => 0.2,   // Reduce repeticiones del mismo término
            'num_predict' => 300,         // Suficiente para respuestas estructuradas JSON
            'seed' => null,               // 🔄 Dejar null para variabilidad controlada
        ];

        $respuesta = Ollama::agent(SystemPromptHelper::UtterancesIntents())->model(config('services.ollama.model'))->prompt($texto)->stream(false)->options($options)->ask();
        $output = $respuesta['response'] ?? $respuesta->body();
        if(is_numeric($output)){
            preg_match('/\d+/', $output, $matches); // funcion para quitar caracteres adicionales y dejar solo numeros
            $numero = $matches[0];  // Resultado: 27
            return $numero; //consiguo el intent
        }else{
            if (preg_match('/^id:\s*\d+$/', $output)) {
                preg_match('/\d+/', $output, $matches); // funcion para quitar caracteres adicionales y dejar solo numeros
                $numero = $matches[0];
                return $numero;
            } else {
                return $output;
            }
        }
        return $output;
    }
}
