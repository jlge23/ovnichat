<?php

namespace App\Http\Controllers;

use App\Jobs\Llama32Job;
use App\Jobs\PhiJob;
use App\Jobs\ProcessGemmaIAJob;
use App\Jobs\SendWhatsAppInteractiveListJob;
use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\Mensaje;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\UsesOllamaOptions;
use App\Traits\UsesIAModelsList;
use Illuminate\Support\Facades\DB;


class MensajeController extends Controller
{
    use UsesOllamaOptions, UsesIAModelsList;

/*     public function empezar(){ //la inicia GEMMA2B
        $mensaje = "Hola, como estas?, que opinas sobre las guerras entre hombres? se breve en tu respuestas";
        //Llama32Job::dispatch('GEMMA2B',$mensaje);
        PhiJob::dispatch('GEMMA2B',$mensaje);
    }
    public function terminar(){
        $mensaje = "Gracias por la conversacion, debo irme. Hasta pronto!";
        //Llama32Job::dispatch('GEMMA2B',$mensaje);
        PhiJob::dispatch('GEMMA2B',$mensaje);
    } */
    public function llama(Request $request){
        Log::info($request->input('prompt'));

        $respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.url"), [
                'model'  => $request->input('model'),
                'prompt' => "Human: ".$request->input('prompt')."\nAssistant:",
                'system' => config('services.ollama.prefix'),
                'stream' => false,
                'options' => $this->ollamaOptions()
        ]),true);
        Log::info($respuesta['response']);
        $r = $respuesta['response'];
        $modelos = $this->modelosLocales();
        return view('welcome', compact('r','modelos'));
    }

    public function mie(){
        $businessModelName = 'ventas_productos';
        $entities = DB::table('business_model_intent')
        ->join('entitie_intent', 'business_model_intent.intent_id', '=', 'entitie_intent.intent_id')
        ->join('entities', 'entitie_intent.entitie_id', '=', 'entities.id')
        ->join('business_models', 'business_model_intent.business_model_id', '=', 'business_models.id')
        ->where('business_models.name', $businessModelName)
        ->select('entities.*')
        ->distinct()
        ->get();
        return $entities;
    }

    public function categorias(){
        $categorias = Categoria::has('productos')->whereNot('id',1)->get();
        $list = $categorias->map(function ($categoria) {
            return [
                'id' => 'cat_' . $categoria->id,
                'title' => $categoria->nombre,
                'description' => $categoria->descripcion ?: 'Sin descripción'
            ];
        })->values()->toArray();
        SendWhatsAppInteractiveListJob::dispatch('593998252990','📚 Categorías disponibles', 'Selecciona una categoría para continuar:', 'GRGROUPS Comercial S.A', $list);
    }

    public function consulta(){
        $nombreProducto = strtolower('HUEVOS');
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
                $q->orWhere('productos.nombre', 'LIKE', "%{$nombreProducto}%");
            }

            if ($marca) {
                $q->orWhere('productos.descripcion', 'LIKE', "%{$marca}%");
            }

            if ($peso) {
                $q->orWhere('productos.descripcion', 'LIKE', "%{$peso}%");
            }

            if ($categoria) {
                $q->orWhere('categorias.nombre', 'LIKE', "%{$categoria}%");
            }

            if ($presentacion) {
                $q->orWhere('embalajes.tipo_embalaje', 'LIKE', "%{$presentacion}%");
            }
        });
        // Verificamos disponibilidad
        $coincidencias = $query->select('productos.*','embalajes.tipo_embalaje','categorias.nombre AS categoria')->get();
        return $coincidencias;
    }
}
