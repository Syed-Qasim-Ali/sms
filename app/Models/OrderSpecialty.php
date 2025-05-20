<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSpecialty extends Model
{
    protected $table = 'order_specialties';
    protected $fillable = ['order_number', 'specialty'];
    public $timestamps = true;
}
