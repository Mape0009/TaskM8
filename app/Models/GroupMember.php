<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $fillable = [
        'groupId',
        'userId',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
