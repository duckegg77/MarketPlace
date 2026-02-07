<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
