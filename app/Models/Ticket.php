<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $primaryKey = 'uuid'; // Define primary key
    public $incrementing = false;  // Disable auto-incrementing
    protected $keyType = 'string'; // Set UUID as string
    protected $guarded = [];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_number', 'order_number');
    }
    public function eventpickdrop()
    {
        return $this->hasMany(EventPickDrop::class, 'ticket_uuid', 'uuid');
    }

    public function ticket_assign()
    {
        return $this->hasMany(TicketAssign::class, 'ticket_id', 'uuid');
    }

    public function users_arrival()
    {
        return $this->hasMany(UsersArrival::class, 'ticket_uuid', 'uuid');
    }
}
