<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'taskName',
        'eventId',
        'location',
        'description',
        'startDate',
        'endDate',
        'userId',
    ];

}
