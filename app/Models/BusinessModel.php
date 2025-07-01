<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessModel extends Model
{
    protected $fillable = ['name', 'description'];
    public function intents() {
        return $this->belongsToMany(Intent::class)->withTimestamps();
    }
}
