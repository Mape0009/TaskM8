<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSettings extends Model
{
    protected $fillable = [
        'userId',
        'newEventSystemNotifications',
        'newShiftSystemNotifications',
        'participantLeaveSystemNotifications',
        'employeeLeaveSystemNotifications',
        'eventDeletedSystemNotifications',
        'groupInvitationSystemNotifications',
        'newEventEmailNotifications',
        'newShiftEmailNotifications',
        'participantLeaveEmailNotifications',
        'employeeLeaveEmailNotifications',
        'eventDeletedEmailNotifications',
        'groupInvitationEmailNotifications',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
