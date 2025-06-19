<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinCode extends Model
{
    protected $fillable = [
        'pincode',
        'mailId',
        'createdAt',
    ];
}
