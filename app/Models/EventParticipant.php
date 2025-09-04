<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'eventId',
        'userId',
        'status',
        'role',
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
enum Role {
    case owner;
    case coOwner;
    case taskManager;
    case taskWorker;
    case participant;
}
