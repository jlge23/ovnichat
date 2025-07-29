<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\SecureInputIAHelper;
use App\Helpers\TextCleanerHelp;
use App\Models\Intent;
use App\Models\Entitie;
use App\Models\Embedding;
use App\Models\EntityIntent; // tabla pivote
use Cloudstudio\Ollama\Facades\Ollama;
use App\Traits\UsesOllamaOptions;

class ImportUtterances extends Command
{
    use UsesOllamaOptions;
    protected $signature = 'utterances:import {--file=utterances-1.json}';
    protected $description = 'Importar utterances desde un archivo JSON, crear embeddings y relaciones';

    public function handle()
    {
        $file = $this->option('file');

        if (!Storage::disk('local')->exists($file)) {
            $this->error("❌ El archivo '$file' no fue encontrado.");
            return;
        }

        $data = json_decode(Storage::disk('local')->get($file), true);

        if (!isset($data['utterances'])) {
            $this->error("❌ El archivo no tiene la estructura esperada.");
            return;
        }

        $this->info("✅ Procesando " . count($data['utterances']) . " utterances...\n");

        foreach ($data['utterances'] as $item) {
            $text = trim($item['text']);
            $intentName = trim($item['intent'] ?? null);
            $entities = $item['entities'] ?? [];
            if (!$intentName || !$text) {
                $this->warn("⚠️ Utterance ignorado por falta de intent: '$text'");
                continue;
            }

            if (!$intentName || !$text) continue;

            // Buscar o crear el intent
            $intent = Intent::firstOrCreate(['intent' => $intentName]);

            // Verificar duplicado textual exacto
            if (Embedding::where('content', $text)->where('intent_id', $intent->id)->exists()) {
                $this->warn("⏩ Ya existe: '$text'");
                continue;
            }

            // Generar embedding vía Ollama

            $entrada = SecureInputIAHelper::sanitizarMensaje($text);
            if (!SecureInputIAHelper::entradaSegura($entrada)) {
                $this->warn("⏩ Mensaje bloqueado por seguridad. Intenta usar lenguaje natural: '$text'");
                continue;
            }

            /* $entradaFiltrada = TextCleanerHelp::normalizarTexto($entrada);
            $system = "Corrige solo errores ortográficos del mensaje siguiente sin modificar el contexto ni sustituir ideas. No reemplaces palabras con sinónimos ni cambies el significado. No inventes nombres ni lugares:\n\n\"$entradaFiltrada\"";
            $respuesta = Ollama::agent($system)->options($this->ollamaOptions())->model('phi3:latest')->prompt($entradaFiltrada)->ask();
            $mensajeCorregido = $respuesta['response'] ?? $entradaFiltrada;
            $prompt = 'search_document: ' . $mensajeCorregido; */
            $prompt = 'search_document: ' . $entrada;
            $response = Ollama::model('nomic-embed-text:v1.5')->embeddings($prompt);

            if (!$response) {
                $this->error("❌ Error generando embedding para '$text'");
                continue;
            }
            $vector = $response['embedding'];

            // Comparar con embeddings similares ya guardados
            $similar = Embedding::all()->first(function ($existing) use ($vector) {
                //$prev = $existing->embedding;
                $prev = is_array($existing->embedding) ? $existing->embedding : json_decode($existing->embedding, true);
                $dot = $normA = $normB = 0;
                for ($i = 0; $i < count($vector); $i++) {
                    $dot += $vector[$i] * $prev[$i];
                    $normA += $vector[$i] ** 2;
                    $normB += $prev[$i] ** 2;
                }
                $similarity = $dot / (sqrt($normA) * sqrt($normB));
                return $similarity > 0.95;
            });

            if ($similar) {
                $this->warn("🔁 Duplicado semántico ignorado: '$text'");
                continue;
            }

            // Guardar embedding
            Embedding::create([
                'content' => $text,
                'embedding' => json_encode($vector),
                'intent_id' => $intent->id,
            ]);

            // Procesar entidades
            foreach ($entities as $entityItem) {
                $nameRaw = $entityItem['entity'] ?? '';
                if (!$nameRaw) continue;

                [$type, $name] = explode(':', $nameRaw) + [null, null];
                $entityModel = Entitie::firstOrCreate(['entity' => $name ?? $type]);
                EntityIntent::firstOrCreate([
                    'intent_id' => $intent->id,
                    'entitie_id' => $entityModel->id,
                ]);
            }

            $this->info("✅ Guardado: '$text' → Intent: {$intent->intent}");
        }

        $this->info("\n🎉 ¡Importación completada con éxito!");
    }
}
