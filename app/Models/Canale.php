<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canale extends Model
{
    protected $fillable = ['canal', 'plataforma', 'token'];

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class);
    }

}
