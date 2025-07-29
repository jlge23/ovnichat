<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EntityIntent extends Pivot
{
    protected $table = 'entitie_intent';

    protected $fillable = ['intent_id', 'entitie_id'];

}
