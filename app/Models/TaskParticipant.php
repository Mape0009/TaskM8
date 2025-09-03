<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskParticipant extends Model
{
    protected $fillable = [
        'taskId',
        'userId',
    ];
}
