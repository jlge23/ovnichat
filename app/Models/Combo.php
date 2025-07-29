<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = ['combo','descripcion','precio','status'];
    public function productos(){
        return $this->belongsToMany(Producto::class, 'combo_productos')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

}
