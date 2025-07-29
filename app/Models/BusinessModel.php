<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessModel extends Model
{
    protected $fillable = ['namodelonegociome', 'description'];
    public function intents() {
        return $this->belongsToMany(Intent::class)->withTimestamps();
    }

    public function empresas() {
        return $this->belongsToMany(Empresa::class, 'empresa_modelos')->withTimestamps();
    }

    public static function mie($business_model_id){ //Modelos de negocio, Intentos y sus entidades
        $ModeloNegocio = BusinessModel::with(['intents.entities'])
        ->where('id', $business_model_id)
        ->get()
        ->map(function ($modelo) {
            return [
                'modelo' => $modelo->modelonegocio,
                'desc_modelo' => $modelo->description,
                'intents_con_entities' => $modelo->intents->map(function ($intent) {
                    return [
                        'nombre' => $intent->intent,
                        'descripcion' => $intent->description,
                        'entidades' => ['entidad' => $intent->entities->pluck('entity')->toArray(), 'descripcion' => $intent->entities->pluck('description')->toArray()]
                    ];
                })
            ];
        });
    }

    // Metodo que trae solo el name del modelo de negocio segun su id
    public static function BsinessModelbyId($id){
        return $ModeloNegocio = BusinessModel::with(['intents.entities'])
        ->where('id', 9)
        ->get()
        ->pluck('intents') // Extrae la colección de intents
        ->flatten()        // Aplana la colección
        ->pluck('modelonegocio')    // Extrae solo el nombre de cada intent
        ->toArray();
    }
}
