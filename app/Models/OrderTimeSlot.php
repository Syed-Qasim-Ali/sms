<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTimeSlot extends Model
{
    protected $table = 'order_time_slot';
    protected $fillable = ['order_number', 'truck_quantity', 'start_time'];
    public $timestamps = true;


    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_number', 'order_number');
    }
}
