<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'email',
        'company_capabilities',
        'company_specialties',
        'phone',
        'address',
        'website',
        'status'
    ];
}
