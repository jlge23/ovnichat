<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $fillable = ['unidad','simbolo'];
    public function productos(){
        return $this->hasMany(Producto::class);
    }
}
