<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinCode extends Model
{
    $fillable = [
        'pincode',
        'mailId',
        'createdAt',
    ];
}
