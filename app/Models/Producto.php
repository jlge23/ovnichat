<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['codigo_sku', 'nombre', 'descripcion', 'unidad_medida', 'precio_detal', 'precio_embalaje', 'stock_actual', 'unidades_por_embalaje', 'categoria_id', 'proveedor_id', 'embalaje_id', 'active', 'image'];
    public function categoria()
    {
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
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_productos')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
