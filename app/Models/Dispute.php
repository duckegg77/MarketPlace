<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $guarded = [];

    protected $casts = [
        'opened_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
}
