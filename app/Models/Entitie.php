<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entitie extends Model
{
    protected $fillable = ['entity', 'description'];

    public function intents() {
        return $this->belongsToMany(Intent::class)->withTimestamps();
    }

}
