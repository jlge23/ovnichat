<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccione extends Model
{
    protected $fillable = ['lead_id','referencia','nombre','direccion_textual','latitud','longitud','es_principal'];
    public function lead(){
        return $this->belongsTo(Lead::class);
    }
}
