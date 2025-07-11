<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;
use BinaryCats\Sku\Concerns\SkuOptions;

class Producto extends Model
{
    use HasSku;

    public function skuOptions() : SkuOptions
    {
        return SkuOptions::make()
            ->from(['nombre', 'descripcion'])
            ->target('sku')
            ->using('-')
            ->forceUnique(true)
            ->generateOnCreate(true)
            ->refreshOnUpdate(false);
    }

    protected $fillable = ['gtin','sku','nombre','descripcion','unidad_medida_id','precio_detal','precio_embalaje','costo_detal','stock_actual','marca_id','unidades_por_embalaje','categoria_id','proveedor_id','embalaje_id','image','active'];
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
}
