<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSiteContact extends Model
{
    protected $table = 'order_site_contact';
    protected $fillable = ['order_number', 'site_contact'];
    public $timestamps = true;
}
