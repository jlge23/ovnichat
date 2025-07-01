<?php

namespace App\Models;

use Dom\Entity;
use Illuminate\Database\Eloquent\Model;

class Intent extends Model
{
    protected $fillable = ['name', 'description', 'priority'];

    public function businessModels() {
        return $this->belongsToMany(BusinessModel::class)->withTimestamps();
    }

    public function entities() {
        return $this->belongsToMany(Entity::class)->withTimestamps();
    }
}
