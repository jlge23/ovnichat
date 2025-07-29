<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embedding extends Model
{
    protected $fillable = [
        'content',
        'embedding',
        'intent_id'
    ];

    protected $casts = [
        'embedding' => 'array', // convierte automáticamente JSON a array en PHP
    ];

    public function intent() {
        return $this->belongsTo(Intent::class);
    }

    // Metodo que trae todas las frases de entrenamiento segun su intent
    public static function EmbeddingWithIntent($intent_id){
        $Embedding = Embedding::with(['intents'])
        ->where('id', 9)
        ->get()
        ->pluck('intents') // Extrae la colección de intents
        ->flatten()        // Aplana la colección
        ->pluck('intent')    // Extrae solo el nombre de cada intent
        ->toArray();
    }
}
