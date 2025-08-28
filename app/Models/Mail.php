<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'senderId',
        'recipientId',
        'sentAt',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipientId');
    }
}
