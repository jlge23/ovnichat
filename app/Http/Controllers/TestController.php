<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Traits\UsesOllamaOptions;
use App\Models\Producto;
use App\Traits\UsesSystemsOptions;
use Illuminate\Support\Str;
use App\Helpers\SecureInputIAHelper;
use App\Helpers\TextCleanerHelp;
use App\Models\Embedding;
use App\Models\Intent;

class TestController extends Controller{

    use UsesOllamaOptions, UsesSystemsOptions;

    public function index(){
        return view('test');
    }

    public function testia(Request $request){

        $toolsMap = [
            'disponibilidad_producto' => ['get_available_products'],
            'nosotros' => ['schedule_appointment'],
            'saludo' => ['get_prices'],
            // Agrega más según tus intents y tools disponibles
        ];

        $tools = [
            [
                "type" => "function",
                "function" => [
                    "name" => "disponibilidad_producto",
                    "description" => "Devuelve una lista de productos disponibles según la intención del usuario",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "query" => [
                                "type" => "string",
                                "description" => "Texto del usuario que describe lo que busca"
                            ],
                        ],
                        "required" => ["query"],
                    ]
                ]
            ],

        ];
        $msg = $request->input('msg');
        $modoTecnico = Str::contains(Str::lower($msg), $this->detectarIntencion($msg));//falta el filtro debido para que se dispare el tools
        $response = Ollama::agent($this->construirSystemPrompt())
        ->model('llama3.1')
        ->options($this->ollamaOptions());
        if ($modoTecnico) {
            $response->tools($tools);
        }
        $response = $response->chat([
            ['role' => 'user', 'content' => $msg]
        ]);
        if (!empty($response['message']['tool_calls'])) {
            foreach ($response['message']['tool_calls'] as $toolCall) {
                if ($toolCall['function']['name'] === 'get_available_products') {
                    $args = $toolCall['function']['arguments'];
                    $resultado = Producto::disponibilidad_producto(
                            $args['producto'] ?? null,
                    );
                    $output = $resultado;
                    //$output = json_encode($response);
                    //return $output;
                    return view('test', compact('output'));
                }
            }
        }else{
            //$output = json_encode($response);
            $output = $response['message']['content'];
            return view('test', compact('output'));
        }

    }

    public function detectarIntencion($msg){
        try {
            $msg = SecureInputIAHelper::sanitizarMensaje($msg);
            if (!SecureInputIAHelper::entradaSegura($msg)) {
                $output = 'Lo sentimos. Mensaje bloqueado por seguridad. Vuelva a plantear su solicitud.';
                Log::warning($output);
                return view('test', compact('output'));
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
                    //SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$existing->intent->intent, $this->msg_id);
                    return $intentTexto;
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
                        //SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                        return $intentTexto;
                    } else {
                        $intentTexto = $embeddingCercano->intent->intent ?? 'desconocido';
                        Log::warning("🔍 Similitud alta, pero no coincide con descripción del intent [$mejorIntent], se asignará el mejor intent");
                        $intentAsignado = $mejorIntent;
                        //SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                        return $intentTexto;
                    }
                } elseif ($mejorSimilitud > 0.60) {
                    $intentAsignado = $mejorIntent;
                    $intentTexto = $embeddingCercano->intent->intent ?? 'desconocido';
                    Log::info('Frase: [' . $entradaFiltrada . '] — Similitud moderada. Intent: [' . $intentTexto . ']');
                    //SendWhatsAppMessageJob::dispatch($this->telefono, 'Intent: '.$intentTexto, $this->msg_id);
                    return $intentTexto;
                } else {
                    Log::warning("❌ No se entendió el mensaje. Similitud baja: " . $mejorSimilitud);
                    return $entradaFiltrada;
                }

                Embedding::create([
                    'content'   => $entradaFiltrada,
                    'embedding' => json_encode($vector),
                    'intent_id' => $intentAsignado
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('Error haciendo Embedding: ' . $e->getMessage());
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
