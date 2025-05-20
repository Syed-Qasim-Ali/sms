<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'job',
        'date',
        'pay_rate',
        'pay_rate_type',
        'start_location',
        'start_location_lat',
        'start_location_lng',
        'end_location',
        'end_location_lat',
        'end_location_lng',
        'material',
        'quantity',
        'instruction',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = Order::generateOrderNumber();
        });
    }

    public static function generateOrderNumber()
    {
        // Get the latest order_number
        $latestOrder = Order::latest('id')->value('order_number');

        if ($latestOrder) {
            // Extract numeric part safely
            preg_match('/\d+$/', $latestOrder, $matches);
            $nextNumber = isset($matches[0]) ? (int) $matches[0] + 1 : 1;
        } else {
            $nextNumber = 1;
        }

        return 'ORD-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function ordercapacity()
    {
        return $this->hasMany(OrderCapability::class, 'order_number', 'order_number');
    }

    public function orderspecialty()
    {
        return $this->hasMany(OrderSpecialty::class, 'order_number', 'order_number');
    }

    public function ordersitecontact()
    {
        return $this->hasMany(OrderSiteContact::class, 'order_number', 'order_number');
    }

    public function ordertimeslot()
    {
        return $this->hasMany(OrderTimeSlot::class, 'order_number', 'order_number');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_number', 'order_number');
    }
}
