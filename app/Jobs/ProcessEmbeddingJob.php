<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
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
use App\Helpers\TextCleanerHelp;
use App\Models\Embedding;
use App\Models\Intent;

class ProcessEmbeddingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesOllamaOptions, UsesSystemsOptions;

    protected string $telefono;
    protected string $nombre;
    protected string $mensaje;
    protected string $msg_id;

    public function __construct(string $telefono, string $nombre, string $mensaje, string $msg_id)
    {
        $this->telefono = $telefono;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
        $this->msg_id = $msg_id;
    }

    public function handle()
    {
        try {
            $msg = SecureInputIAHelper::sanitizarMensaje($this->mensaje);
            if (!SecureInputIAHelper::entradaSegura($msg)) {
                $output = 'Lo sentimos. Mensaje bloqueado por seguridad. Vuelva a plantear su solicitud.';
                Log::warning($output);
                SendWhatsAppMessageJob::dispatch($this->telefono, $output, $this->msg_id);
                return;
            }

            $entradaFiltrada = TextCleanerHelp::normalizarTexto($msg);
            $prompt = 'search_document: ' . $entradaFiltrada;
            $respuesta = Ollama::model('nomic-embed-text:v1.5')->embeddings($prompt);
            $vector = $respuesta['embedding'];

            $mejorSimilitud = 0;
            $mejorIntent = null;
            $embeddingCercano = null;
            $existe = false;
            $conflictosAltaSimilitud = [];

            $todosLosEmbeddings = Embedding::query();

            foreach ($todosLosEmbeddings->get() as $existing) {
                if (TextCleanerHelp::normalizarTexto($existing->content) === $entradaFiltrada) {
                    $existe = true;
                    $intentTexto = $existing->intent->intent ?? 'desconocido';
                    Log::info('Frase idéntica: [' . $entradaFiltrada . '] — Intent: ' . $intentTexto);
                    SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$existing->intent->intent, $this->msg_id);
                    break;
                }

                $prev = is_array($existing->embedding) ? $existing->embedding : json_decode($existing->embedding, true);
                $dot = $normA = $normB = 0;

                for ($i = 0; $i < count($vector); $i++) {
                    $dot += $vector[$i] * $prev[$i];
                    $normA += $vector[$i] ** 2;
                    $normB += $prev[$i] ** 2;
                }

                $similarity = $dot / (sqrt($normA) * sqrt($normB));

                if ($similarity > 0.95) {
                    $conflictosAltaSimilitud[] = $existing->intent_id;
                }

                if ($similarity > $mejorSimilitud) {
                    $mejorSimilitud = $similarity;
                    $mejorIntent = $existing->intent_id;
                    $embeddingCercano = $existing;
                }
            }

            if (!$existe) {
                $intentAsignado = null;

                if (count(array_unique($conflictosAltaSimilitud)) > 1) {
                    Log::warning('⚠️ Conflicto semántico: múltiples intents con alta similitud > 0.95 — ' . implode(', ', $conflictosAltaSimilitud));
                    $intentAsignado = null;
                } elseif ($mejorSimilitud > 0.75) {
                    // Reconfirmar usando la descripción del intent
                    if ($this->confirmarIntentPorDescripcion($entradaFiltrada, $mejorIntent)) {
                        $intentAsignado = $mejorIntent;
                        $intentTexto = $embeddingCercano->intent->intent ?? 'desconocido';
                        Log::info('✅ Reconfirmación por descripción: [' . $entradaFiltrada . '] — Intent: [' . $intentTexto . ']');
                        SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                    } else {
                        $intentTexto = $embeddingCercano->intent->intent ?? 'desconocido';
                        Log::warning("🔍 Similitud alta, pero no coincide con descripción del intent [$mejorIntent], se asignará el mejor intent");
                        $intentAsignado = $mejorIntent;
                        SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                    }
                } elseif ($mejorSimilitud > 0.60) {
                    $intentAsignado = $mejorIntent;
                    $intentTexto = $embeddingCercano->intent->intent ?? 'desconocido';
                    Log::info('Frase: [' . $entradaFiltrada . '] — Similitud moderada. Intent: [' . $intentTexto . ']');
                    SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                } else {
                    Log::warning("❌ No se entendió el mensaje. Similitud baja: " . $mejorSimilitud);
                }

                Embedding::create([
                    'content'   => $entradaFiltrada,
                    'embedding' => json_encode($vector),
                    'intent_id' => $intentAsignado
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('Error en ProcessEmbeddingJob: ' . $e->getMessage());
        }
    }

    protected function confirmarIntentPorDescripcion($mensajeUsuario, $intentIdDetectado): bool
    {
        $promptUsuario = 'search_document: ' . $mensajeUsuario;
        $embeddingUsuario = Ollama::model('nomic-embed-text:v1.5')->embeddings($promptUsuario)['embedding'];

        $intent = Intent::find($intentIdDetectado);
        if (!$intent || empty($intent->description)) {
            Log::warning("Intent no encontrado o sin descripción. ID: $intentIdDetectado");
            return false;
        }

        $promptDescripcion = 'search_document: ' . $intent->description;
        $embeddingDescripcion = Ollama::model('nomic-embed-text:v1.5')->embeddings($promptDescripcion)['embedding'];

        $dot = $normA = $normB = 0;
        for ($i = 0; $i < count($embeddingUsuario); $i++) {
            $dot += $embeddingUsuario[$i] * $embeddingDescripcion[$i];
            $normA += $embeddingUsuario[$i] ** 2;
            $normB += $embeddingDescripcion[$i] ** 2;
        }
        $similitud = $dot / (sqrt($normA) * sqrt($normB));

        Log::info("🧮 Similitud con descripción del intent [$intent->intent]: $similitud");

        return $similitud > 0.80;
    }

}
