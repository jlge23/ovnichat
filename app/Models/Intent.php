<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Entitie;

class Intent extends Model
{
    protected $fillable = ['name', 'description', 'priority'];

    public function businessModels() {
        return $this->belongsToMany(BusinessModel::class)->withTimestamps();
    }

    public function entities() {
        return $this->belongsToMany(Entitie::class)->withTimestamps();
    }
}
