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

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_participants', 'taskId', 'userId');
    }
}