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

    public function eventpickdrop()
    {
        return $this->hasMany(EventPickDrop::class, 'ticket_uuid', 'uuid');
    }
}
