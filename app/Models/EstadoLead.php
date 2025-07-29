<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class EstadoLead extends Model
{
    protected $fillable = ['codigo','estado','descripcion'];

    public function leads(){
        return $this->hasMany(Lead::class, 'estado_lead_id');
    }
}
