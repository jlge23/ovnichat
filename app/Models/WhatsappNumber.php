<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappNumber extends Model
{
    protected $fillable = ['empresa_id', 'numero', 'nombre_opcional', 'estado', 'webhook_url', 'api_token'];

    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }

    public static function rules() {
        return [
            'numero' => ['required', 'string', 'unique:whatsapp_numbers,numero', 'regex:/^\+\d{10,15}$/'],
            'empresa_id' => ['required', 'exists:empresas,id'],
            'estado' => ['in:activo,inactivo'],
        ];
    }


}
