<?php

namespace App\Http\Controllers;

use App\Helpers\AutocurarIntentsHelper;
use Illuminate\Http\Request;
use App\Traits\UsesIAModelsList;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Helpers\SecureInputIAHelper;
use App\Models\Embedding;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    use UsesIAModelsList;
    public function index(){
        //$modelos = $this->modelosLocales();
        //return $modelos;
        return view('welcome');
    }

    public function llama(Request $request)
    {
        $entrada = SecureInputIAHelper::sanitizarMensaje($request->input('prompt'));
        if (!SecureInputIAHelper::entradaSegura($entrada)) {
            $output = 'Mensaje bloqueado por seguridad. Intenta usar lenguaje natural.';
            return back()->withErrors(['error' => $output]);
        }
        $entradaFiltrada = $entrada;
        $prompt = 'search_document: ' . $entradaFiltrada;

        $respuesta = Ollama::model('nomic-embed-text:v1.5')->embeddings($prompt);
        $vector = $respuesta['embedding'];

        $cosineSimilarity = function ($vec1, $vec2) {
            $dot = $normA = $normB = 0;
            foreach ($vec1 as $i => $val) {
                if (count($vec1) !== count($vec2)) {
                    // Puedes saltarte ese vector, loguearlo, o lanzar advertencia
                    continue; // evitar crash
                }

                $dot += $val * $vec2[$i];
                $normA += $val ** 2;
                $normB += $vec2[$i] ** 2;
            }
            return $dot / (sqrt($normA) * sqrt($normB));
        };

        // Verificar duplicado semántico
        $duplicado = false;
        foreach (Embedding::all() as $registro) {
            $otroVector = is_array($registro->embedding)
                ? $registro->embedding
                : json_decode($registro->embedding, true);

            $similitud = $cosineSimilarity($vector, $otroVector);
            if ($similitud >= 0.95) {
                $duplicado = true;
                break;
            }
        }

        if (!$duplicado) {
            $autocuracion = AutocurarIntentsHelper::autocurar($entradaFiltrada);
            if(is_numeric($autocuracion) && (!$autocuracion == 0)){
                Embedding::create([
                    'content' => $entradaFiltrada,
                    'embedding' => json_encode($vector),
                    'intent_id' => $autocuracion
                ]);
            }else{
                $output = "No se detecto intentos que coincidan con esta expresión: ".$entradaFiltrada. " salida: ".$autocuracion;
                Log::warning($output);
                return back()->withErrors(['error' => $output]);
            }
        }

        // Mostrar todas las comparaciones + intent + entities
        $comparaciones = [];
        foreach (Embedding::with('intent.entities')->get() as $registro) {
            $otroVector = is_array($registro->embedding)
                ? $registro->embedding
                : json_decode($registro->embedding, true);

            $similitud = $cosineSimilarity($vector, $otroVector);

            $comparaciones[] = [
                'texto' => $registro->content ?? $registro->content,
                'similitud' => round($similitud, 4),
                'intent' => $registro->intent->intent ?? null,
                'entities' => $registro->intent && $registro->intent->entities
                    ? $registro->intent->entities->pluck('entity')->toArray()
                    : []
            ];
        }

        return view('welcome', compact('comparaciones'));
    }

}
