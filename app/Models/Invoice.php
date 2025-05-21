<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'total_amount',
        'payment_status'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
