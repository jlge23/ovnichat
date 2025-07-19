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
use App\Traits\UsesSystemsOptions;
use App\Helpers\SaludoHelper;
use App\Models\Categoria;
use App\Models\Producto;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Str;

class ProcessOllamaIAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesOllamaOptions, UsesSystemsOptions;

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
            //$respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.urlGenerate"), [
            $respuesta = Http::timeout(100)->post(config("services.ollama.urlChat"), [
                'model'  => config("services.ollama.model"),
                //'prompt' => $this->mensaje,
                'system' => $this->construirSystemPrompt(),
                'stream' => false,
                'options' => $this->ollamaOptions(),
                'messages' => [
                    [ 'role' => 'system', 'content' => $this->construirSystemPrompt()],
                    [ 'role' => 'user',   'content' =>  $this->mensaje],
                    [ 'role' => 'assistant', 'content' => 'tu respuesta...' ]
                ]
            ]);
            //]), true);
            Log::info($respuesta);
            SendWhatsAppMessageJob::dispatch($this->telefono, $respuesta->json()['message']['content']);
        } catch (\Throwable $e) {
            Log::error('Error en ProcessOllamaIAJob: ' . $e->getMessage());
        }
    }

}
