<?php

namespace App\Http\Controllers;

use App\Helpers\SecureInputIAHelper;
use App\Jobs\Llama32Job;
use App\Jobs\PhiJob;
use App\Jobs\ProcessGemmaIAJob;
use App\Jobs\SendWhatsAppInteractiveListJob;
use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\Embedding;
use App\Models\Entitie;
use App\Models\Intent;
use App\Models\Marca;
use App\Models\Mensaje;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\UsesOllamaOptions;
use Illuminate\Support\Facades\DB;
use App\Traits\UsesSystemsOptions;
use Cloudstudio\Ollama\Facades\Ollama;


class MensajeController extends Controller
{
    use UsesOllamaOptions, UsesSystemsOptions;

    public function Intents(){
        // 🧩 Intents con sus entidades asociadas
        $embeddingsIntents = Embedding::with('intent')->get()->map(function ($embeddings) {
            return "- Expresiones: {$embeddings->content} | Intent: {$embeddings->intent->intent} → id: {$embeddings->intent->id}";
        })->implode("\n");

        // 🎯 Prompt con estructura semántica real
            $system = <<<PROMPT
                Tu nombre es **OvniBot**. Eres un buscador de Expresiones e intents

                🧾 “Usa únicamente las Expresiones e intents registrados en el sistema; no respondas mas nada.”
                "primero, evalua bien todas las expreciones y luego selecciona el intent mas identico a la expresion"

                Analiza los Intents disponibles, según la frase dada, la descripcion de cada Intent describe su funcion, usala como referencia:
                Expresiones e Intents disponibles:
                {$embeddingsIntents}

                y responde solo con el id => id:

                Si no encuentras un intent que coincida, responde '0'
            PROMPT;
        return $system;
    }
    public function LLM()
    {
        $embeddings = Embedding::select('embeddings.content')->whereNull('intent_id')->orderBy('id', 'desc')->get();

        $resultados = [];
        if(!$embeddings->isEmpty()){
            foreach ($embeddings as $registro) {
                $texto = $registro->content;

                $options = [
                    'temperature' => 0.0,           // 🔒 Baja aleatoriedad, evita creatividad excesiva
                    'top_p' => 1.0,                 // 🔒 Mantiene cobertura completa sin limitar tokens
                    'repeat_penalty' => 1.1,        // Penaliza redundancia moderadamente
                    'presence_penalty' => 0.3,      // Evita inventar nuevas ideas ausentes
                    'frequency_penalty' => 0.2,     // Reduce repeticiones del mismo término
                    'num_predict' => 300,           // Suficiente para respuestas estructuradas JSON
                    'seed' => null,                 // 🔄 Dejar null para variabilidad controlada
                ];
                $respuesta = Ollama::agent($this->Intents())->model(config('services.ollama.model'))->stream(false)->prompt($texto)->options($options)->ask();
                $output = $respuesta->json('response') ?? $respuesta->body();
                $resultados['datos'][] = [
                    'content' => $texto." - ".$output['response'],
                ];
            }
            return json_encode($resultados);
        }
        return "No hay frases huerfanas (sin intentos asociados)";
    }

    public function llama(Request $request)
    {
        $prompt = SecureInputIAHelper::sanitizarMensaje($request->input('prompt'));
        if (!SecureInputIAHelper::entradaSegura($prompt)) {
            $output = 'Mensaje bloqueado por seguridad. Intenta usar lenguaje natural.';
            return back()->withErrors(['error' => $output]);
        }
        $prompt = 'search_document: ' . $prompt;

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
            Embedding::create([
                'content' => $request->input('prompt'),
                'embedding' => json_encode($vector),
                'intent_id' => $this->autocurar($request->input('prompt'))
            ]);
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

    public function autocurar($texto)
    {
        $options = [
            'temperature' => 0,           // 🔒 Baja aleatoriedad, evita creatividad excesiva
            'top_p' => 1.0,                 // 🔒 Mantiene cobertura completa sin limitar tokens
            'repeat_penalty' => 1.1,        // Penaliza redundancia moderadamente
            'presence_penalty' => 1,      // Evita inventar nuevas ideas ausentes
            'frequency_penalty' => 0.2,     // Reduce repeticiones del mismo término
            'num_predict' => 300,           // Suficiente para respuestas estructuradas JSON
            'seed' => null,                  // 🔄 Dejar null para variabilidad controlada
        ];

        $respuesta = Ollama::agent($this->Intents())->model(config('services.ollama.model'))->prompt($texto)->stream(false)->options($options)->ask();
        $output = $respuesta['response'] ?? $respuesta->body();
        if(is_numeric($output)){
            preg_match('/\d+/', $output, $matches); // funcion para quitar caracteres adicionales y dejar solo numeros
            $numero = $matches[0];  // Resultado: 27
            return $numero; //consiguo el intent
        }else{
            if (preg_match('/^id:\s*\d+$/', $output)) {
                preg_match('/\d+/', $output, $matches); // funcion para quitar caracteres adicionales y dejar solo numeros
                $numero = $matches[0];
                return $numero;
            } else {
                return $output;
            }
        }
        return $output;
    }

    public function mie(){
        $businessModelName = 'ventas_productos';
        $entities = DB::table('business_model_intent')
        ->join('entitie_intent', 'business_model_intent.intent_id', '=', 'entitie_intent.intent_id')
        ->join('entities', 'entitie_intent.entitie_id', '=', 'entities.id')
        ->join('business_models', 'business_model_intent.business_model_id', '=', 'business_models.id')
        //->where('business_models.modelonegocio', $businessModelName)
        ->select('entities.*')
        ->distinct()
        ->get();
        return $entities;
    }

    public function productos(){
        // Listado de productos disponibles
        $productos = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->where('productos.active',true)
            ->select('productos.id','productos.producto','productos.descripcion','stock_actual','embalajes.embalaje AS embalaje', 'categorias.categoria AS categoria','marcas.marca AS marca')
        ->get()
        ->map(function ($producto){
            return [
                'content' => "Producto: {$producto->producto}\nDescripcion: {$producto->descripcion}\nStock: {$producto->stock_actual}\nEmbalaje: {$producto->embalaje}\nCategoria: {$producto->categoria}\nMarca: {$producto->marca}"
            ];
        })->values()->toJson();
        return $productos;
    }

    public function categorias(){
        $categorias = Categoria::has('productos')->whereNot('id',1)->get()
        ->map(function ($categoria) {
            return [
                'content' => "Categoria: {$categoria->categoria}\nDescripcion: {$categoria->descripcion}\n",
            ];
        })->values()->toJson();
        return $categorias;
        //SendWhatsAppInteractiveListJob::dispatch('593983774093','📚 Categorías disponibles', 'Selecciona una categoría para continuar:', 'GRGROUPS Comercial S.A', $list);
    }

    public function consulta(){
        // Listado de Categorias disponibles
        $categorias = Categoria::has('productos')->whereNot('id',1)->get()
        ->map(function ($categoria) {
            return [
                'id' => 'cat_' . $categoria->id,
                'title' => $categoria->categoria,
                'description' => $categoria->descripcion ?: 'Sin descripción'
            ];
        });

        // Listado de Marcas disponibles
        $marcas = Marca::has('productos')->whereNot('id',1)->get()
        ->map(function ($marca) {
            return [
                'marca' => $marca->marca,
            ];
        });

        // Listado de productos disponibles
        $productos = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->where('productos.active',true)
            ->select('productos.id','productos.producto','productos.descripcion','stock_actual','embalajes.embalaje AS embalaje', 'categorias.categoria AS categoria','marcas.marca AS marca')
        ->get()
        ->map(function ($producto){
            return [
                'idProducto' => $producto->id,
                'nombreProducto' => $producto->producto,
                'descripcionProducto' => $producto->descripcion,
                'stock_actual' => $producto->stock_actual,
                'embalaje' => $producto->embalaje,
                'categoriaProducto' => $producto->categoria,
                'marcaProducto' => $producto->marca,
            ];
        });

        $ModeloNegocio = BusinessModel::with(['intents.entities'])
            ->where('id', 9)
            ->get()
            ->map(function ($modelo) {
                return [
                    'modelo' => $modelo->modelonegocio,
                    'desc_modelo' => $modelo->description,
                    'intents_con_entities' => $modelo->intents->map(function ($intent) {
                        return [
                            'nombre' => $intent->intent,
                            'descripcion' => $intent->description,
                            'entidades' => ['entidad' => $intent->entities->pluck('entity')->toArray(), 'descripcion' => $intent->entities->pluck('description')->toArray()]
                        ];
                    })
                ];
            });

        if ($productos->isEmpty()) {
            $productos = "No hay inventario de productos disponibles.";
        }
        if ($ModeloNegocio->isEmpty()) {
            $ModeloNegocio = "No hay configuración del modelo de negocio actual.";
        }
        if ($marcas->isEmpty()) {
            $marcas = "No hay información de marcas registradas.";
        }
        if ($categorias->isEmpty()) {
            $categorias = "No hay información de categorias registradas.";
        }

        return <<<PROMPT
            Tu nombre es OvniBot!. Eres un agente de atencion al cliente especializado en {$ModeloNegocio->pluck('desc_modelo')->implode(', ')}.
            Tu tarea es detectar si el mensaje del usuario corresponde a alguno de los siguientes intents y entities con sus descripciones:
            {$ModeloNegocio->pluck('intents_con_entities')} y extraer las entidades relevantes.

            Si el cliente requiere informacion sobre Productos, categorias de productos o Marcas de productos, aqui tienes un listado de cada uno

            **Productos disponibles:**
            {$productos->toJson(JSON_PRETTY_PRINT)}

            **Categorias de productos disponibles:**
            {$categorias->toJson(JSON_PRETTY_PRINT)}

            **Macras de productos disponibles:**
            {$marcas->toJson(JSON_PRETTY_PRINT)}

            **Usa el idioma español para todo**

            responde dee manera natural y amistosa, como si fueras una persona conversando.
        PROMPT;

        /* $nombreProducto = strtolower('HUEVOS');
        $marca = 'TERRA';
        $peso = null;
        $categoria = '🍄 Fungi y legumbres';
        $cantidadSolicitada = 1;
        $presentacion = null;
        $query = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
            ->where('productos.active', 1);

        // Agrupamos condiciones opcionales en un solo bloque
        $query->where(function ($q) use ($nombreProducto, $marca, $peso, $categoria, $presentacion) {
            if ($nombreProducto) {
                $q->orWhere('productos.producto', 'LIKE', "%{$nombreProducto}%");
            }

            if ($marca) {
                $q->orWhere('productos.descripcion', 'LIKE', "%{$marca}%");
            }

            if ($peso) {
                $q->orWhere('productos.descripcion', 'LIKE', "%{$peso}%");
            }

            if ($categoria) {
                $q->orWhere('categorias.categoria', 'LIKE', "%{$categoria}%");
            }

            if ($presentacion) {
                $q->orWhere('embalajes.embalaje', 'LIKE', "%{$presentacion}%");
            }
        });
        // Verificamos disponibilidad
        $coincidencias = $query->select('productos.*','embalajes.embalaje','categorias.categoria AS categoria')->get();
        return $coincidencias;

        ->where(function ($q) use ($nombreProducto, $marca, $peso, $categoria, $presentacion) {
                    if ($nombreProducto)  $q->orWhere('productos.producto', 'LIKE', "%{$nombreProducto}%");
                    if ($marca)           $q->orWhere('marcas.marca', 'LIKE', "%{$marca}%");
                    if ($peso)            $q->orWhere('productos.descripcion', 'LIKE', "%{$peso}%");
                    if ($categoria)       $q->orWhere('categorias.categoria', 'LIKE', "%{$categoria}%");
                    if ($presentacion)    $q->orWhere('embalajes.embalaje', 'LIKE', "%{$presentacion}%");
                })
        */
    }
}
