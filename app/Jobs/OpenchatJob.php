<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use App\Traits\UsesOllamaOptions;

class OpenchatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesOllamaOptions;

    protected string $mensaje;
    protected string $modelo;

    public function __construct(string $modelo, string $mensaje)
    {
        $this->mensaje = $mensaje;
        $this->modelo = $modelo;
    }

    public function handle(): void
    {
        try {
            Log::info("{$this->modelo} dice: ", [$this->mensaje]);
            if (Str::contains(Str::lower($this->mensaje), ['adiós', 'nos vemos', 'Hasta pronto!'])) {
                Log::info("{$this->modelo} ha cerrado el ciclo de conversación.");
                return;
            }
            $prompt = $this->mensaje;
            $respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.url"), [
                'model'  => config("services.ollama.model4"),
                'prompt' => $prompt,
                'system' => config('services.ollama.prefix'),
                'stream' => false,
                'options' => $this->ollamaOptions()
            ]), true);
            $texto = $respuesta['response'];
            $texto = mb_convert_encoding($texto, 'UTF-8', 'auto');
            if (trim($texto) === '') {
                $texto = 'Respuesta vacía del modelo.';
            }
            //Llama32Job::dispatch('PHI',$texto);
            //PhiJob::dispatch('LLAMA3.2',$texto);
            //Gemma2bJob::dispatch('PHI',$texto);
        } catch (\Throwable $e) {
            Log::error('Error en OpenchatJob: ' . $e->getMessage());
        }
    }
}
