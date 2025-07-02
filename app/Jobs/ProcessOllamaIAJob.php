<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\WhatsappEvent;
use App\Traits\UsesOllamaOptions;
use App\Helpers\SaludoHelper;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Intent;
use App\Models\Entitie;
use Illuminate\Support\Str;


class ProcessOllamaIAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesOllamaOptions;

    protected string $telefono;
    protected string $nombre;
    protected string $mensaje;

    public function __construct(string $telefono, string $nombre, string $mensaje)
    {
        $this->telefono = $telefono;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
    }

    public function handle(): void
    {
        try {
            Log::info("{$this->nombre} escribe a Ollama:", [$this->mensaje]);
            $respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.url"), [
                'model'  => config("services.ollama.model"),
                'prompt' => $this->mensaje,
                'system' => $this->construirSystemPrompt(),
                'stream' => false,
                'options' => $this->ollamaOptions()
            ]), true);
            $texto = $respuesta['response'] ?? '';
            $texto = mb_convert_encoding(trim($texto), 'UTF-8', 'auto');

            // Validación: ¿es un JSON válido con clave "intent"?
            $formatoJson = false;
            $jsonData = null;

            if (Str::startsWith($texto, '{') && Str::endsWith($texto, '}')) {
                try {
                    $jsonData = json_decode($texto, true, 512, JSON_THROW_ON_ERROR);
                    if (is_array($jsonData) && array_key_exists('intent', $jsonData)) {
                        $formatoJson = true;
                    }
                } catch (\Throwable $e) {
                    $formatoJson = false;
                }
            }

            if ($formatoJson) {
                Log::info('🧠 Ollama respondió con INTENT JSON:', $jsonData);
                event(new WhatsappEvent("INTENT: " . json_encode($jsonData, JSON_PRETTY_PRINT)));
                echo $jsonData;
            } else {
                Log::info('💬 Ollama respondió naturalmente: ' . $texto);
                event(new WhatsappEvent("IA: " . $texto));
                echo $texto;
            }

            //SendWhatsAppMessageJob::dispatch($this->telefono, $texto);
        } catch (\Throwable $e) {
            Log::error('Error en ProcessOllamaIAJob: ' . $e->getMessage());
        }
    }

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
