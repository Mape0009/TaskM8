<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'taskId',
        'userId',
        'startTime',
        'endTime',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'taskId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
