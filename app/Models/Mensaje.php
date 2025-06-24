<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $fillable = [
        'mensaje',
        'tipo_mensaje', 'metadatos', 'inicio_conversacion',
        'leido', 'canale_id', 'conversacione_id','lead_id'
    ];

    protected $casts = [
        'metadatos' => 'array',
        'leido' => 'boolean',
        'inicio_conversacion' => 'datetime',
    ];

    public function canal(){
        return $this->belongsTo(Canale::class);
    }

    public function lead(){
        return $this->belongsTo(Lead::class);
    }

    public function direcciones(){
        return $this->hasMany(Direccione::class);
    }

}
