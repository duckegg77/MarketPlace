<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'shipping_address_encrypted' => 'encrypted:array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
