<?php

namespace App\Http\Controllers;

use App\Helpers\SystemPromptHelper;
use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\Embedding;
use App\Models\Marca;
use App\Models\Producto;
use App\Traits\UsesOllamaOptions;
use Illuminate\Support\Facades\DB;
use App\Traits\UsesSystemsOptions;
use Cloudstudio\Ollama\Facades\Ollama;


class MensajeController extends Controller
{
    use UsesOllamaOptions, UsesSystemsOptions;

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
                $respuesta = Ollama::agent(SystemPromptHelper::UtterancesIntents())->model(config('services.ollama.model'))->stream(false)->prompt($texto)->options($options)->ask();
                $output = $respuesta->json('response') ?? $respuesta->body();
                $resultados['datos'][] = [
                    'content' => $texto." - ".$output['response'],
                ];
            }
            return json_encode($resultados);
        }
        return "No hay frases huerfanas (sin intentos asociados)";
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
