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
use App\Jobs\ProcessOllamaIAJob;
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
            if (strpos($this->mensaje, 'cat_') === 0) {
                $categoria_id = str_replace('cat_', '', $this->mensaje);
                $respuesta = Categoria::productosXcategoria($categoria_id);
                event(new WhatsappEvent($respuesta));
                SendWhatsAppMessageJob::dispatch($this->telefono, $respuesta);
                return;
            }else{
                Log::info("{$this->nombre} escribe a WitAI:", [$this->mensaje]);
                $response = Http::withToken(config('services.witai.token'))
                    ->withHeaders([
                        'Accept' => 'application/json',
                    ])
                    ->get(config('services.witai.url'), [
                        'q' => $this->mensaje,
                    ]);
                Log::warning($response);
                $analisis = $response->json();
                $intencion = (!empty($analisis)) ? data_get($analisis, 'intents.0.name') : null;
                //$confianza = data_get($analisis, 'intents.0.confidence');
                //$mensaje = $this->mensaje;
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
                    'preguntar_producto' => Categoria::SeleccionarCategorias($this->telefono),
                    'disponibilidad_producto' => $this->procesarPedido(data_get($analisis, 'entities')),
                    'nosotros' => Categoria::listarCategorias(),
                    null => ProcessOllamaIAJob::dispatch($this->telefono,$this->nombre,$this->mensaje),
                };
                (!empty($respuesta)) ? SendWhatsAppMessageJob::dispatch($this->telefono, $respuesta) : ProcessOllamaIAJob::dispatch($this->telefono,$this->nombre,$this->mensaje);
                //event(new WhatsappEvent($respuesta));
                return;
            }

        } catch (\Throwable $e) {
            Log::error('Error en ProcessWitAIJob: ' . $e->getMessage());
        }
    }

    public function procesarPedido(array $entities){
        $productosBrutos = $entities['producto:producto'] ?? [];
        $cantidades      = $entities['wit$number:number'] ?? [];
        $marcas          = $entities['marca:marca'] ?? [];
        $pesos           = $entities['peso:pesoCantidad'] ?? [];
        $categorias      = $entities['categoria:categoria'] ?? [];
        $presentaciones  = $entities['presentacion:presentacion'] ?? [];

        $mensajes = collect();
        if($productosBrutos){
            foreach ($productosBrutos as $i => $entity) {
                $nombreProducto     = $entity['body'];
                $marca              = $marcas[$i]['body'] ?? null;
                $peso               = $pesos[$i]['body'] ?? null;
                $categoria          = $categorias[$i]['body'] ?? null;
                $cantidadSolicitada = $cantidades[$i]['value'] ?? 1;
                $presentacion       = $presentaciones[$i]['body'] ?? null;

                $coincidencias = Producto::query()
                    ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                    ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
                    ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                    ->where('productos.active',true)
                    ->where(function ($q) use ($nombreProducto, $marca, $peso, $categoria, $presentacion) {
                        if ($nombreProducto)  $q->orWhereRaw('productos.producto ILIKE ?', ['%'.$nombreProducto.'%']);
                        if ($marca)           $q->orWhereRaw('marcas.marca ILIKE ?', ['%'.$marca.'%']);
                        if ($peso)            $q->orWhereRaw('productos.descripcion ILIKE ?', ['%'.$peso.'%']);
                        if ($categoria)       $q->orWhereRaw('categorias.categoria ILIKE ?', ['%'.$categoria.'%']);
                        if ($presentacion)    $q->orWhereRaw('embalajes.embalaje ILIKE ?', ['%'.$presentacion.'%']);
                    })
                    ->select('productos.*', 'embalajes.embalaje', 'categorias.categoria AS categoria','marcas.marca')
                    ->get();

                $disponibles = $coincidencias->filter(fn($p) => $p->stock_actual >= $cantidadSolicitada);
                $mensaje = $disponibles->isEmpty()
                    ? "❌ No tenemos disponible {$cantidadSolicitada} unidad(es) de *{$nombreProducto}*"
                        . ($marca ? " marca {$marca}" : "")
                        . ($peso ? " con peso {$peso}" : "") . ".\n"
                    : "📦 *Aquí están los productos disponibles:*\n\n" .
                        $disponibles->map(fn($p) =>
                            "✅ {$cantidadSolicitada} unidad(es) de *{$p->nombrproductoe} {$p->descripcion}*" .
                            ($marca ? " marca {$marca}" : "") .
                            ($peso ? " con peso {$peso}" : "") .
                            " disponible: *stock: [{$p->stock_actual}]*. SKU: {$p->sku}. al detal: \${$p->costo_detal}. por *{$p->embalaje}*: \${$p->precio_embalaje}\n"
                        )->implode("\n");

                $mensajes->push($mensaje);
            }

            $mensajeFinal = $mensajes->filter()->implode("\n");

            if (blank($mensajeFinal)) {
                Log::info('No hay coincidencias que mostrar');
                return '🟡 No se encontraron coincidencias con los productos solicitados.';
            }
            return $mensajeFinal;
        }elseif($categorias){
            Categoria::SeleccionarCategorias($this->telefono);
            $mensajeFinal = "📦 *Aquí esta una lista de Categorias disponibles:*\n\n";
            return $mensajeFinal;
        }
    }

}
