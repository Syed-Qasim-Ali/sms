<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'truck_number',
        'truck_type',
        'registration_number',
        'model',
        'truck_capabilities',
        'truck_specialties',
        'brand',
        'year',
        'capacity',
        'fuel_type',
        'driver_name',
        'driver_contact',
        'status',
        'image',
        'documents',
    ];
}
