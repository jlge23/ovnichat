<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['empresa', 'codigo', 'direccion', 'telefono_contacto'];

    public function usuarios() {
        return $this->hasMany(User::class);
    }

    public function whatsappNumbers() {
        return $this->hasMany(WhatsappNumber::class);
    }

    public function businessModels() {
        return $this->belongsToMany(BusinessModel::class, 'empresa_modelos')->withTimestamps();
    }

    public static function rules() {
        return [
            'empresa' => ['required', 'string', 'max:255'],
            'codigo' => ['required', 'string', 'unique:empresas,codigo'],
            'direccion' => ['nullable', 'string'],
            'telefono_contacto' => ['nullable', 'string'],
        ];
    }


}
