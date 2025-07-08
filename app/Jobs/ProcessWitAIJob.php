<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use App\Events\WhatsappEvent;
use App\Helpers\SaludoHelper;
use App\Models\Categoria;
use App\Models\Producto;

class ProcessWitAIJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $telefono;
    protected string $nombre;
    protected string $mensaje;

    public function __construct(string $telefono, string $nombre, string $mensaje)
    {
        $this->telefono = $telefono;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
    }

    public function handle(): void
    {
        try {
            Log::info("{$this->nombre} escribe a WitAI:", [$this->mensaje]);
            $response = Http::withToken(config('services.witai.token'))
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get(config('services.witai.url'), [
                    'q' => $this->mensaje,
                ]);

            $analisis = $response->json();
            Log::info($analisis);
            $intencion = data_get($analisis, 'intents.0.name');
            $confianza = data_get($analisis, 'intents.0.confidence');
            $mensaje = $this->mensaje;
            $respuesta = match ($intencion) {
                'saludo' => SaludoHelper::saludoDelDia($this->nombre),
                'consulta_horario' => '🕘 Atendemos de *9 a.m. a 6 p.m.* de lunes a viernes.',
                'consulta_ubicacion' => '📍 Estamos en *Guayaquil*, cerca del malecón. ¿Quieres que te comparta el mapa?',
                'consulta_precio' => '💰 Nuestros precios varían según el producto. ¿Cuál te interesa?',
                'pedido_asistencia' => '🛠️ Claro, dime cuál es el problema y te ayudo enseguida.',
                'agradecimiento' => '🙏 ¡Un gusto ayudarte! Si necesitas algo más, aquí estoy.',
                'despedida' => '👋 ¡Hasta luego '.$this->nombre.'! Que tengas un buen día ☀️',
                'consulta_forma_pago' => '💳 Aceptamos *transferencias, efectivo y tarjeta*. ¿Cómo prefieres pagar?',
                'confirmacion_pedido' => '✅ Perfecto, te lo confirmo. Enseguida te paso los detalles.',
                'cancelacion' => '❌ Listo, hemos cancelado tu pedido. Avísanos si necesitas algo más.',
                'reclamo' => '😟 Lamento eso. Vamos a solucionarlo lo antes posible. ¿Me das más detalles?',
                'consulta_tiempo_entrega' => '🚚 En *Guayaquil* entregamos el mismo día. ¿Dónde estás ubicado tú?',
                'consulta_garantia' => '🛡️ Sí, ofrecemos garantía. ¿Sobre qué producto necesitas información?',
                'consulta_promocion' => '🎉 ¡Tenemos varias promos! ¿Qué producto te interesa revisar?',
                'preguntar_producto' => '🔍 *¿Qué producto estás buscando exactamente?*',
                'disponibilidad_producto' => $this->procesarPedido(data_get($analisis, 'entities')),
                'nosotros' => $this->listarCategorias(),
                null => '🤖 Disculpa, no entendí tu mensaje. ¿Quieres ver nuestras opciones o hablar con un asesor?',
                '' => '🤖 Disculpa, no entendí tu mensaje. ¿Quieres ver nuestras opciones o hablar con un asesor?',
            };
            event(new WhatsappEvent($respuesta));
            SendWhatsAppMessageJob::dispatch($this->telefono, $respuesta);

            return;
        } catch (\Throwable $e) {
            Log::error('Error en ProcessWitAIJob: ' . $e->getMessage());
        }
    }

    public function procesarPedido(array $entities){
        Log::info($entities);
        $productosBrutos = $entities['producto:producto'] ?? [];
        $cantidades      = $entities['wit$number:number'] ?? [];
        $marcas          = $entities['marca:marca'] ?? [];
        $pesos           = $entities['peso:pesoCantidad'] ?? [];
        $categorias      = $entities['categoria:categoria'] ?? [];
        $presentaciones  = $entities['presentacion:presentacion'] ?? [];

        $mensajes = collect();

        foreach ($productosBrutos as $i => $entity) {
            $nombreProducto     = strtolower($entity['body']);
            $marca              = $marcas[$i]['body'] ?? null;
            $peso               = $pesos[$i]['body'] ?? null;
            $categoria          = $categorias[$i]['body'] ?? null;
            $cantidadSolicitada = $cantidades[$i]['value'] ?? 1;
            $presentacion       = $presentaciones[$i]['body'] ?? null;

            $coincidencias = Producto::query()
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
                ->where('productos.active', 1)
                ->where(function ($q) use ($nombreProducto, $marca, $peso, $categoria, $presentacion) {
                    if ($nombreProducto)  $q->orWhere('productos.nombre', 'LIKE', "%{$nombreProducto}%");
                    if ($marca)           $q->orWhere('productos.descripcion', 'LIKE', "%{$marca}%");
                    if ($peso)            $q->orWhere('productos.descripcion', 'LIKE', "%{$peso}%");
                    if ($categoria)       $q->orWhere('categorias.nombre', 'LIKE', "%{$categoria}%");
                    if ($presentacion)    $q->orWhere('embalajes.tipo_embalaje', 'LIKE', "%{$presentacion}%");
                })
                ->select('productos.*', 'embalajes.tipo_embalaje', 'categorias.nombre AS categoria')
                ->get();

            Log::info($coincidencias);

            $disponibles = $coincidencias->filter(fn($p) => $p->stock_actual >= $cantidadSolicitada);

            $mensaje = $disponibles->isEmpty()
                ? "❌ No tenemos disponible {$cantidadSolicitada} unidad(es) de *{$nombreProducto}*"
                    . ($marca ? " marca {$marca}" : "")
                    . ($peso ? " con peso {$peso}" : "") . ".\n"
                : "📦 *Aquí están los productos disponibles:*\n\n" .
                    $disponibles->map(fn($p) =>
                        "✅ {$cantidadSolicitada} unidad(es) de *{$p->nombre} {$p->descripcion}*" .
                        ($marca ? " marca {$marca}" : "") .
                        ($peso ? " con peso {$peso}" : "") .
                        " disponible: *stock: [{$p->stock_actual}]*. SKU: {$p->codigo_sku}. al detal: \${$p->precio_detal}. por *{$p->tipo_embalaje}*: \${$p->precio_mayor}\n"
                    )->implode("\n");

            $mensajes->push($mensaje);
        }

        $mensajeFinal = $mensajes->filter()->implode("\n");

        if (blank($mensajeFinal)) {
            Log::info('No hay coincidencias que mostrar');
            return '🟡 No se encontraron coincidencias con los productos solicitados.';
        }

        return $mensajeFinal;
    }

    // Metodo para consula de categorias de productos en el stock
    public function listarCategorias(){
        $categorias = Categoria::has('productos')->get();
        $mensaje = "🏢 *En GRGROUP Comercial S.A.* nos dedicamos a la comercialización de alimentos de tierra y mar\n";
        $mensaje .= "Te ofrecemos productos clasificados en las siguientes categorías:\n\n";

        foreach ($categorias as $cat) {
            $nombre = strtoUpper($cat->nombre);
            $mensaje .= "*{$cat->nombre}*: {$cat->descripcion}.\n";
        }
        $mensaje .= "\n✨ *Calidad y variedad para ti.*";
        $mensaje .= "\n🔍 *¿Qué producto estás buscando exactamente?*";

        return $mensaje;
    }




    // Metodo para consula de productos disponibles en el stock
    /* public function procesarPedido(array $entities){
        $productosBrutos = $entities['producto:producto'] ?? [];
        $cantidades = $entities['wit$number:number'] ?? [];
        $marcas = $entities['marca:marca'] ?? [];
        $pesos = $entities['peso:pesoCantidad'] ?? [];
        $categorias = $entities['categoria:categoria'] ?? [];
        $presentacion = $entities['presentacion:presentacion'] ?? [];

        $resultados = [];

        foreach ($productosBrutos as $i => $entity) {
            $nombreProducto = strtolower($entity['body']);
            $marca = $marcas[$i]['body'] ?? null;
            $peso = $pesos[$i]['body'] ?? null;
            $categoria = $categorias[$i]['body'] ?? null;
            $cantidadSolicitada = $cantidades[$i]['value'] ?? 1;
            $presentacion = $presentacion[$i]['body'] ?? null;
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
            Log::info($coincidencias);
            $disponibles = $coincidencias->filter(fn($p) => $p->stock_actual >= $cantidadSolicitada);
            if (!$disponibles->isEmpty()) {
                $detalle = $disponibles->map(fn($p) => [
                    'codigo_sku' => $p->codigo_sku,
                    'nombre' => $p->nombre,
                    'descripcion' => $p->descripcion,
                    'precio_detal' => $p->precio_detal,
                    'precio_mayor' => $p->precio_mayor,
                    'stock_actual' => $p->stock_actual,
                    'presentacion' => $p->tipo_embalaje,
                    'categoria' => $p->categoria
                ]);
            } else {
                $detalle = [];
            }
            $resultados[] = [
                'solicitado' => [
                    'producto' => $nombreProducto,
                    'marca' => $marca,
                    'peso' => $peso,
                    'categoria' => $categoria,
                    'presentacion' => $presentacion,
                    'cantidad_solicitada' => $cantidadSolicitada,
                ],
                'disponible' => !$disponibles->isEmpty(),
                'productos' => $detalle,
                'mensaje' => $disponibles->isEmpty()
                    ? "❌ No tenemos disponible {$cantidadSolicitada} unidad(es) de *{$nombreProducto}*\n"
                        . ($marca ? " marca {$marca}" : "")
                        . ($peso ? " con peso {$peso}" : "") . ".\n"
                    : "📦 *Aquí están los productos disponibles:*\n\n"
                        . $disponibles->map(fn($p) =>
                        "✅ {$cantidadSolicitada} unidad(es) de *{$p->nombre} {$p->descripcion}*"
                        . ($marca ? " marca {$marca}" : "")
                        . ($peso ? " con peso {$peso}" : "")
                        . " disponible: *stock: [{$p->stock_actual}]*. SKU: {$p->codigo_sku}. al detal: \${$p->precio_detal}. por *{$p->tipo_embalaje}*: {$p->precio_mayor}\n\n"
                    )->implode(''),
            ];
        }
        if(collect($resultados)->pluck('mensaje')->implode(" ") === ''){
            "No hay nada de mostrar: ".collect($resultados)->pluck('mensaje')->implode(" ");
            return;
        }
        if(collect($resultados)->pluck('mensaje')->implode(" ") === null){
            "No hay nada de mostrar: ".collect($resultados)->pluck('mensaje')->implode(" ");
            return;
        }
        return collect($resultados)->pluck('mensaje')->implode(" ");
    } */
}
