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
    ];
}
