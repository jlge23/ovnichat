<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = ['marca'];
    public function Productos()
    {
        return $this->hasMany(Producto::class,'marca_id','id');
    }
}
