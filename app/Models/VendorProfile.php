<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payout_details_encrypted' => 'encrypted:array',
        'approved_at' => 'datetime',
    ];
}
