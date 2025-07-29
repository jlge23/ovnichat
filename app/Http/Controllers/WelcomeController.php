<?php

namespace App\Http\Controllers;

use App\Helpers\AutocurarIntentsHelper;
use Illuminate\Http\Request;
use App\Traits\UsesIAModelsList;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Helpers\SecureInputIAHelper;
use App\Helpers\TextCleanerHelp;
use App\Models\Embedding;
use App\Models\Intent;
use Illuminate\Support\Facades\Log;
use App\Traits\UsesOllamaOptions;

class WelcomeController extends Controller
{
    use UsesIAModelsList, UsesOllamaOptions;
    public function index(){
        //$modelos = $this->modelosLocales();
        //return $modelos;
        return view('welcome');
    }

    public function llama(Request $request)
    {
        $entrada = SecureInputIAHelper::sanitizarMensaje($request->input('prompt')) ?? null;
        if (!SecureInputIAHelper::entradaSegura($entrada)) {
            $output = 'Mensaje bloqueado por seguridad. Intenta usar lenguaje natural.';
            return back()->withErrors(['error' => $output]);
        }

        $entradaFiltrada = TextCleanerHelp::normalizarTexto($entrada);
        /* $system = "Corrige solo errores ortográficos del mensaje siguiente sin modificar el contexto ni sustituir ideas. No reemplaces palabras con sinónimos ni cambies el significado. No inventes nombres ni lugares:\n\n\"$entradaFiltrada\"";
        $respuesta = Ollama::agent($system)->options($this->ollamaOptions())->model('phi3:latest')->prompt($entradaFiltrada)->ask();
        $mensajeCorregido = $respuesta['response'] ?? $entradaFiltrada;
        Log::info("📘 Reescritura por IA: $mensajeCorregido");

        $prompt = 'search_document: ' . $mensajeCorregido; */
        $prompt = 'search_document: ' . $entradaFiltrada;

        $respuesta = Ollama::model('nomic-embed-text:v1.5')->embeddings($prompt);
        $vector = $respuesta['embedding'];

        $mejorSimilitud = 0;
        $mejorIntent = null;
        $existe = false;
        $todosLosEmbeddings = Embedding::query();
        foreach ($todosLosEmbeddings->get() as $existing) {
            if (TextCleanerHelp::normalizarTexto($existing->content) === $entradaFiltrada) {
                // Ya existe exactamente esta frase
                $existe = true;
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

            if ($similarity > $mejorSimilitud) {
                $mejorSimilitud = $similarity;
                $mejorIntent = $existing->intent_id;
            }
        }
        if(!$existe){
            if ($mejorSimilitud > 0.75) {
                Embedding::create([
                    'content'    => $entradaFiltrada,
                    'embedding'  => json_encode($vector),
                    'intent_id'  => $mejorIntent
                ]);
            }else{

                $intentAsignado = ($mejorSimilitud > 0.60) ? $mejorIntent : null;
                Embedding::create([
                    'content'   => $entradaFiltrada,
                    'embedding' => json_encode($vector),
                    'intent_id' => $intentAsignado
                ]);
            }
        }

        // Mostrar todas las comparaciones + intent + entities
        $cosineSimilarity = function ($vec1, $vec2) {
            $dot = $normA = $normB = 0;
            foreach ($vec1 as $i => $val) {
                if (count($vec1) !== count($vec2)) {
                    continue; // evitar crash
                }

                $dot += $val * $vec2[$i];
                $normA += $val ** 2;
                $normB += $vec2[$i] ** 2;
            }
            return $dot / (sqrt($normA) * sqrt($normB));
        };

        $comparaciones = [];
        foreach ($todosLosEmbeddings->with('intent.entities')->get() as $registro) {
            $otroVector = is_array($registro->embedding)
                ? $registro->embedding
                : json_decode($registro->embedding, true);

            $similitud = $cosineSimilarity($vector, $otroVector);

            $comparaciones[] = [
                'embeddingId' => $registro->id,
                'texto' => $registro->content ?? $registro->content,
                'similitud' => round($similitud, 4),
                'intent' => $registro->intent->intent ?? null,
                'entities' => $registro->intent && $registro->intent->entities
                    ? $registro->intent->entities->pluck('entity')->toArray()
                    : []
            ];
        }

        $intents = Intent::all();
        return view('welcome', compact('comparaciones', 'intents'));
    }

    public function asignarIntent(Request $request, Embedding $embedding){
        $embedding->intent_id = $request->input('intent_id');
        $embedding->save();
        return view('welcome')->with('success', 'Expresión ['.$embedding->content.'] con intent ['.$embedding->intent->intent.'] asociado!');
    }

    public function destroy(Embedding $embedding){
        $embedding->delete();
        return redirect()->route('welcome');
    }

}
