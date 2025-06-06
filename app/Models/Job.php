<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{

    // public $timestamps = false;

    protected $fillable = ['user_id', 'name', 'image', 'description', 'status', 'created_at'];
}
