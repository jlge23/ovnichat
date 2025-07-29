<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendWhatsAppInteractiveListJob;
use Illuminate\Support\Facades\Log;

class Categoria extends Model
{
    protected $fillable = ['categoria','descripcion'];
    public function productos(){
        return $this->hasMany(Producto::class);
    }

    // Metodo para consula de categorias de productos en el stock
    public static function listarCategorias(){
        $categorias = Categoria::has('productos')->get();
        $mensaje = "🏢 *En GRGROUP Comercial S.A.* nos dedicamos a la comercialización de alimentos de tierra y mar\n";
        $mensaje .= "Te ofrecemos productos clasificados en las siguientes categorías:\n\n";

        foreach ($categorias as $cat) {
            $categoria = strtoUpper($cat->categoria);
            $mensaje .= "*{$categoria}*: {$cat->descripcion}.\n";
        }
        $mensaje .= "\n✨ *Calidad y variedad para ti.*";
        $mensaje .= "\n🔍 *¿Qué producto estás buscando exactamente?*";

        return $mensaje;
    }

    //Metodo para listar todas las categorias
    public static function SeleccionarCategorias($telefono,$msgId){
        $categorias = Categoria::has('productos')->whereNot('id',1)->get();
        $list = $categorias->map(function ($categoria) {
            return [
                'id' => 'cat_' . $categoria->id,
                'title' => $categoria->categoria,
                'description' => $categoria->descripcion ?: 'Sin descripción'
            ];
        })->values()->toArray();
        SendWhatsAppInteractiveListJob::dispatch($telefono,'📚 Categorías disponibles', 'Selecciona una categoría para continuar:', 'GRGROUPS Comercial S.A', $list, $msgId);
        return;
    }
    //Metodo para consultar productos por categoria
    public static function productosXcategoria($categoria_id){
        $msj = collect();
        $disponibles = Producto::query()
        ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
        ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
        ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
        ->where('productos.active',true)
        ->where('productos.categoria_id',$categoria_id)
        ->select('productos.*', 'embalajes.embalaje', 'categorias.categoria AS categoria','marcas.marca')
        ->get();

        $mensaje = $disponibles->isEmpty()
        ? "❌ No tenemos disponible productos disponibles de esta Categoría"
        : "📦 *Aquí están los productos disponibles:*\n\n" .
        $disponibles->map(fn($p) =>
        "✅ *{$p->producto} {$p->descripcion}*" .
        " disponible: *stock: [{$p->stock_actual}]*. SKU: {$p->sku}. al detal: \${$p->costo_detal}. por *{$p->embalaje}*: \${$p->precio_embalaje}\n"
        )->implode("\n");

        $msj->push($mensaje);

        $mensajeFinal = $msj->filter()->implode("\n");

        if (blank($mensajeFinal)) {
            Log::info('No hay coincidencias que mostrar');
            return '🟡 No se encontraron coincidencias con los productos solicitados.';
        }
        return $mensajeFinal;
    }
}
