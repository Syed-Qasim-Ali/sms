<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    protected $fillable = ['user_id', 'name', 'status'];
}
