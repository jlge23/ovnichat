<?php

namespace App\Http\Controllers;

use App\Helpers\AutocurarIntentsHelper;
use App\Helpers\SystemPromptHelper;
use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Traits\UsesOllamaOptions;
use Illuminate\Support\Facades\DB;
use App\Traits\UsesSystemsOptions;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Intent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class MensajeController extends Controller
{
    use UsesOllamaOptions, UsesSystemsOptions;

    public function mie(Request $request)
    {
        $ver = AutocurarIntentsHelper::autocurar('Quiero tener una consulta con el odontologo');
        return $ver;

        // Mensaje entrante (puede venir de WhatsApp)
        $mensaje = $request->input('mensaje') ?? 'Mano quiero comprar dos harinas de 1 kilo, dos atun de agua de 200 gramos y dos cajas de refrescos';
        $intent = [
            [
                "intent" => " consultar_tipo_consulta"
            ],
            [
                "intent" => " consultar_medico"
            ],
            [
                "intent" => " reagendar_consulta"
            ],
            [
                "intent" => " cancelar_consulta"
            ],
            [
                "intent" => " consultar_resultados_examenes"
            ],
            [
                "intent" => " consultar_seguro_aceptado"
            ],
            [
                "intent" => " crear_reserva"
            ],
            [
                "intent" => " consultar_reserva"
            ],
            [
                "intent" => " modificar_reserva"
            ],
            [
                "intent" => " cancelar_reserva"
            ],
            [
                "intent" => " consultar_disponibilidad"
            ],
            [
                "intent" => " consultar_politicas_reserva"
            ],
            [
                "intent" => " consultar_estado_pedido"
            ],
            [
                "intent" => " reporte_problema_producto"
            ],
            [
                "intent" => " solicitar_devolucion"
            ],
            [
                "intent" => " consultar_politicas_servicio"
            ],
            [
                "intent" => " reclamo_facturacion"
            ],
            [
                "intent" => " actualizar_datos_cliente"
            ],
            [
                "intent" => " contactar_agente"
            ],
            [
                "intent" => " buscar_vehiculo"
            ],
            [
                "intent" => " agendar_test_drive"
            ],
            [
                "intent" => " consultar_financiamiento"
            ],
            [
                "intent" => " cotizar_vehiculo"
            ],
            [
                "intent" => " consultar_servicios_postventa"
            ],
            [
                "intent" => " agendar_servicio_tecnico"
            ],
            [
                "intent" => " buscar_producto"
            ],
            [
                "intent" => " consultar_precio"
            ],
            [
                "intent" => " realizar_pedido"
            ],
            [
                "intent" => " consultar_promociones"
            ],
            [
                "intent" => " reservar_evento"
            ],
            [
                "intent" => " consultar_membresias"
            ],
            [
                "intent" => " contactar_personal"
            ],
            [
                "intent" => " reservar_cita"
            ],
            [
                "intent" => " consultar_servicios"
            ],
            [
                "intent" => " cancelar_cita"
            ],
            [
                "intent" => " opinion_servicio"
            ],
            [
                "intent" => " buscar_paquete_turistico"
            ],
            [
                "intent" => " consultar_precio_paquete"
            ],
            [
                "intent" => " reserva_paquete"
            ],
            [
                "intent" => " consultar_itinerario"
            ],
            [
                "intent" => " asesoria_viaje"
            ],
            [
                "intent" => " buscar_propiedad"
            ],
            [
                "intent" => " agendar_visita"
            ],
            [
                "intent" => " publicar_propiedad"
            ],
            [
                "intent" => " realizar_compra"
            ],
            [
                "intent" => " consultar_envio"
            ],
            [
                "intent" => " postventa_soporte"
            ],
            [
                "intent" => " buscar_promociones"
            ],
            [
                "intent" => " consultar_detalle_promocion"
            ],
            [
                "intent" => " filtrar_promociones"
            ],
            [
                "intent" => " consultar_cupones"
            ],
            [
                "intent" => " consultar_promociones_personalizadas"
            ],
            [
                "intent" => " consultar_formas_aplicar_promocion"
            ],
            [
                "intent" => " saludo"
            ],
            [
                "intent" => " despedida"
            ],
            [
                "intent" => " agradecimiento"
            ],
            [
                "intent" => " nosotros"
            ],
            [
                "intent" => " reclamo"
            ],
            [
                "intent" => " consulta_horario"
            ],
            [
                "intent" => " consulta_ubicacion"
            ],
            [
                "intent" => " consulta_precio"
            ],
            [
                "intent" => " consulta_promocion"
            ],
            [
                "intent" => " consulta_forma_pago"
            ],
            [
                "intent" => " consulta_tiempo_entrega"
            ],
            [
                "intent" => " consulta_garantia"
            ],
            [
                "intent" => " pedido_asistencia"
            ],
            [
                "intent" => " cancelacion"
            ],
            [
                "intent" => " preguntar_producto"
            ],
            [
                "intent" => " disponibilidad_producto"
            ],
            [
                "intent" => " confirmacion_pedido"
            ]
        ];
        // ✅ Convertir el array en cadena JSON antes de insertarlo en el heredoc
        $jsonIntents = json_encode($intent, JSON_PRETTY_PRINT);

        // Generar prompt personalizado para análisis gramatical
        $prompt = <<<EOT
                analisa el mensaje del user

            1. Identifica el sujeto, el verbo y el predicado.
            2. Describe brevemente la acción que realiza el sujeto.
            3. Compara esta acción con las intenciónes registradas: {$jsonIntents}
            4. Indica si la intención del mensaje coincide con las intenciónes registradas.
            5. Organiza la cantidad de productos, unidad de medida, si  fuere necesario y la un listado
            6. Devuelve un porcentaje estimado de coincidencia semántica entre el mensaje y la intención.
            7. Responde de maner clara, precisa, completa y organizada, con lenguaje variado y natural (no robotico ni repetitivo)

            Ejemplo de formato de respuesta:
            - Sujeto: ...
            - Verbo: ...
            - Predicado: ...
            - Acción detectada: ...
            - Intención detectada: ...
            - ¿Coincide?: Sí/No
            - Porcentaje de coincidencia: ...%
        EOT;

        // Definir el rol de recepcionista en español
        $agent = Ollama::agent($prompt)->options(['temperature' => 1])
        //$agent = Ollama::agent($prompt)->options(['temperature' => 0.2])
            ->model('gemma3:1b');
            //$agent->prompt($mensaje);

        // Crear mensaje estructurado con el prompt
        $messages = [
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => $mensaje]
        ];


        // Ejemplo: si el mensaje contiene palabras relacionadas con productos, puedes también agregar tools
        $triggerTools = Str::contains(Str::lower($mensaje), [
            'temperatura', 'perro', 'producto', 'gato', 'caraotas', 'socio', 'marca', 'categoría'
        ]);

        if ($triggerTools) {
            $tools = [
                [
                    "type" => "function",
                    "function" => [
                        "name" => "get_current_weather",
                        "description" => "Get the current weather for a specific location",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "location" => [
                                    "type" => "string",
                                    "description" => "The city and country, e.g. Tokyo, Japan",
                                ],
                                "unit" => [
                                    "type" => "string",
                                    "description" => "Temperature unit",
                                    "enum" => ["celsius", "fahrenheit"],
                                ],
                            ],
                            "required" => ["location"],
                        ],
                    ],
                ]
            ];

            $agent->tools($tools);
        }

        // Ejecutar la consulta
        $respuesta = $agent->chat($messages); http://127.0.0.1:11434/api/chat
        //$respuesta = $agent->ask(); // http://127.0.0.1:11434/api/generate

        // Devolver respuesta JSON
        return response()->json([
            'mensaje_original' => $mensaje,
            'respuesta_estructura' => $respuesta['response'] ?? $respuesta
        ]);

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

    public function construirSystemPrompt(): string
    {

        // Listado de Productos disponibles

        $productos = Producto::query()
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
        ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
        ->where('productos.active', true)
        ->select(
            'productos.producto',
            'productos.descripcion',
            'productos.costo_detal',
            'productos.precio_detal',
            'productos.precio_embalaje',
            'productos.stock_actual',
            'embalajes.embalaje AS embalaje',
            'categorias.categoria AS categoria',
            'marcas.marca AS marca'
        )->get()
        ->map(function($producto){
            return "- Producto: {$producto->producto} | Descripción: {$producto->descripcion} | Precio detal: {$producto->precio_detal} | Precio por embalaje: {$producto->precio_embalaje} | Stock:  {$producto->stock_actual} | Embalaje: {$producto->embalaje} | Categoria: {$producto->categoria} | Marca: {$producto->marca}";
        })->implode("\n");

        // Listado de Categorias disponibles
        $categorias = Categoria::has('productos')->whereNot('id',1)->get()
        ->map(function ($categoria) {
            return "Categoria: {$categoria->categoria} | Descripción: {$categoria->descripcion}";
        })->implode("\n");

        // Listado de Marcas disponibles
        $marcas = Marca::has('productos')->whereNot('id',1)->get()
        ->map(function ($marca) {
            return "Marca: {$marca->marca}";
        })->implode("\n");

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



        if ($ModeloNegocio->isEmpty()) {
            $ModeloNegocio = "No hay configuración del modelo de negocio actual.";
        }

        return <<<PROMPT
            Tu nombre es OvniBot. Eres un agente de atención al cliente especializado en {$ModeloNegocio->pluck('desc_modelo')->implode(', ')}. Tu propósito es asistir con cordialidad, claridad y empatía en consultas relacionadas con productos del inventario.

            Tu tarea es detectar si el mensaje del usuario corresponde a alguno de los siguientes intents y entities con sus descripciones:
            {$ModeloNegocio->pluck('intents_con_entities')} y extraer las entidades relevantes.

            tambien debes ayudar al cliente si te pide información sobre productos, categorías o marcas. Dispones de inventario detallado en tres niveles:

            **Productos disponibles**
            {$productos}

            **Categorías disponibles:**
            {$categorias}

            **Marcas disponibles:**
            {$marcas}

            ---

            📌 Si el cliente menciona explícitamente algún producto, muestra de inmediato la información del producto.

            🚫 Si no detectas coincidencias, responde de forma cortés indicando que no hay registros, y si lo consideras útil, sugiere alternativas similares que sí estén disponibles.

            💬 Si el mensaje es un saludo o conversación casual, responde de forma natural, amistosa y sin invocar herramientas. Puedes hacer preguntas suaves para continuar la charla si es adecuado.

            🌐 Responde siempre en español, con un estilo conversacional, accesible y humano. Evita lenguaje técnico salvo que el cliente lo solicite.

            🎯 Tu objetivo es sonar útil, simpático y confiable. No des información vacía ni generes respuestas extensas si no agregan valor.

        PROMPT;

    }
}
