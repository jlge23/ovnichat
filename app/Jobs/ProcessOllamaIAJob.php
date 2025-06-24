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

class ProcessOllamaIAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $instruccion = config("services.ollama.prefix");
            $prompt = $instruccion . ' ' . $this->mensaje;
            $respuesta = Http::post(config("services.ollama.url"), [
                'model'  => config("services.ollama.model"),
                'prompt' => $prompt,
            ]);
            $texto = '';
            $fragmentos = explode("\n", $respuesta->body());
            foreach ($fragmentos as $linea) {
                if (trim($linea) === '') continue;
                $chunk = json_decode($linea, true);
                if (isset($chunk['response'])) {
                    $texto .= $chunk['response'];
                }
            }
            $texto = mb_convert_encoding($texto, 'UTF-8', 'auto');
            if (trim($texto) === '') {
                $texto = 'Respuesta vacía del modelo.';
            }
            Log::info('Responde Ollama: ', [$texto]);
            event(new WhatsappEvent("IA: " . $texto));
            SendWhatsAppMessageJob::dispatch($this->telefono, $texto);
        } catch (\Throwable $e) {
            Log::error('Error en ProcessOllamaIAJob: ' . $e->getMessage());
        }
    }
}
