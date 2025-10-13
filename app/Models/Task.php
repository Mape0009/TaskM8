<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'taskName',
        'eventId',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'taskId');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'shifts', 'taskId', 'userId');
    }
}