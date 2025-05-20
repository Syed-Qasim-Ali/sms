<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCapability extends Model
{
    protected $table = 'order_capabilities';
    protected $fillable = ['order_number', 'capability'];
    public $timestamps = true;
}
