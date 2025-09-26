<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'taskName',
        'eventId',
        'description',
        'start_time',
        'end_time',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'shifts', 'taskId', 'userId');
    }
}