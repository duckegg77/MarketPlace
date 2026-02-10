<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    protected $hidden = ['encrypted_body'];

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }
}
