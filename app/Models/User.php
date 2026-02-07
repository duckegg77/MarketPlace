<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret', 'pin_hash', 'public_pgp_key'];

    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
