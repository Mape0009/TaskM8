<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'eventName',
        'startDate',
        'endDate',
        'description',
        'location',
    ];
}
