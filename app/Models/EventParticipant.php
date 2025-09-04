<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'eventId',
        'userId',
        'status',
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
