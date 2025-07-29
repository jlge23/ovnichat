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
use App\Helpers\SecureInputIAHelper;

class ProcessOllamaIAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesOllamaOptions, UsesSystemsOptions;

    protected string $telefono;
    protected string $nombre;
    protected string $mensaje;
    protected string $msg_id;

    public function __construct(string $telefono, string $nombre, string $mensaje, $msg_id)
    {
        $this->telefono = $telefono;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
        $this->msg_id = $msg_id;
    }

    public function handle(): void
    {
        try {
            $msg = SecureInputIAHelper::sanitizarMensaje($this->mensaje);
            if (!SecureInputIAHelper::entradaSegura($msg)) {
                $output = 'Lo sentimos. Mensaje bloqueado por seguridad. vuelva a plantear su solicitud.';
                Log::warning($output);
                //SendWhatsAppMessageJob::dispatch($this->telefono, $output);
            }
            Log::info("{$this->nombre} escribe a Ollama:", [$this->mensaje]);
            //$respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.urlGenerate"), [
            $respuesta = Ollama::agent($this->construirSystemPrompt())
            ->stream(false)
            ->options($this->ollamaOptions())
            ->model('llama3.2')
            ->chat(['role' => 'user', 'content' => $msg]);
            Log::info($respuesta);
            //SendWhatsAppMessageJob::dispatch($this->telefono, $respuesta->json()['message']['content'],$this->msg_id);
        } catch (\Throwable $e) {
            Log::error('Error en ProcessOllamaIAJob: ' . $e->getMessage());
        }
    }

}
