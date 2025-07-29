<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['proveedor','contacto','telefono','email'];
    protected $table = "proveedores";
    public function productos(){
        return $this->hasMany(Producto::class);
    }
}
