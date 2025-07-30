<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embalaje extends Model
{
    //use HasFactory;
    protected $fillable = ['nombre','descripcion'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
