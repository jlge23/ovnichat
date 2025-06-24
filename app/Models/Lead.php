<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['psid','plataforma','nombre','telefono','email','notas','estado_lead_id'];

    public function estado(){
        return $this->belongsTo(EstadoLead::class, 'estado_lead_id');
    }

    public function mensajes(){
        return $this->hasMany(Mensaje::class);
    }

    public function direcciones(){
        return $this->hasMany(Direccione::class);
    }
}
