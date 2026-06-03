<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'userId',
        'eventId',
        'message',
        'isRead',
    ];

    protected $casts = [
        'isRead' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
