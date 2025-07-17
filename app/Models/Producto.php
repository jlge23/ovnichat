<?php

namespace App\Models;

use App\Jobs\SendWhatsAppMessageJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasSku;
    protected $fillable = ['gtin','sku','producto','descripcion','unidad_medida_id','precio_detal','precio_embalaje','costo_detal','stock_actual','marca_id','unidades_por_embalaje','categoria_id','proveedor_id','embalaje_id','image','active'];

    public function skuOptions() : SkuOptions
    {
        return SkuOptions::make()
            ->from(['producto', 'descripcion'])
            ->target('sku')
            ->using('-')
            ->forceUnique(true)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function embalaje()
    {
        return $this->belongsTo(Embalaje::class);
    }

    public function combos(){
        return $this->belongsToMany(Combo::class, 'combo_productos')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function unidadMedida(){
        return $this->belongsTo(UnidadMedida::class);
    }

    //Consulta de items
    public static function disponibilidad_producto($item){
        try {
            $item = mb_strtoupper($item);
            $query = Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('embalajes', 'productos.embalaje_id', '=', 'embalajes.id')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->where('productos.active', true)
            ->where(function ($q) use ($item) {
                    if ($item)  $q->orWhere('productos.producto', 'LIKE', "%{$item}%");
                    if ($item)  $q->orWhere('categorias.categoria', 'LIKE', "%{$item}%");
                    if ($item)  $q->orWhere('marcas.marca', 'LIKE', "%{$item}%");
            })
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
            );
            $query->groupBy(
                'productos.id',
                'productos.producto',
                'productos.descripcion',
                'productos.stock_actual',
                'productos.costo_detal',
                'productos.precio_detal',
                'productos.precio_embalaje',
                'embalajes.embalaje',
                'categorias.categoria',
                'marcas.marca'
            );
            $resultados = $query->get();
            $items = collect(); // 🧃 Aquí van solo las líneas individuales

            foreach($resultados as $res){
                $items->push("✅ *{$res->producto} {$res->descripcion}* - marca: *{$res->marca}* - categoría *{$res->categoria}* [stock: *{$res->stock_actual}*]. precio detal: \${$res->costo_detal}. por *{$res->embalaje}*: \${$res->precio_embalaje}");
            }

            if ($items->isEmpty()) {
                return "❌ No tenemos *{$item}* disponible\n";
            }

            $mensajeFinal = "📦 *Aquí están los productos disponibles:*\n\n" . $items->implode("\n\n");

            return $mensajeFinal;
            /* $mensajeFinal = $mensajes->filter()->implode("\n");
            if (blank($mensajeFinal)) {
                Log::info('No hay coincidencias que mostrar');
                return '🟡 No se encontraron coincidencias con los productos solicitados.';
            } */
            //return $mensajeFinal;
        } catch (\Exception $e) {
            return ['error violento' => $e->getMessage()];
        }
    }
}


/* ->where(function ($q) use ($nombreProducto, $marca, $peso, $categoria, $presentacion) {
                    if ($nombreProducto)  $q->orWhere('productos.producto', 'LIKE', "%{$nombreProducto}%");
                    if ($marca)           $q->orWhere('marcas.marca', 'LIKE', "%{$marca}%");
                    if ($peso)            $q->orWhere('productos.descripcion', 'LIKE', "%{$peso}%");
                    if ($categoria)       $q->orWhere('categorias.categoria', 'LIKE', "%{$categoria}%");
                    if ($presentacion)    $q->orWhere('embalajes.embalaje', 'LIKE', "%{$presentacion}%");
                }) */
