<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{

    protected $guarded = [];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
