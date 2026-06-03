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

    protected $casts = [
        'newEventSystemNotifications' => 'boolean',
        'newShiftSystemNotifications' => 'boolean',
        'participantLeaveSystemNotifications' => 'boolean',
        'employeeLeaveSystemNotifications' => 'boolean',
        'eventDeletedSystemNotifications' => 'boolean',
        'groupInvitationSystemNotifications' => 'boolean',
        'newEventEmailNotifications' => 'boolean',
        'newShiftEmailNotifications' => 'boolean',
        'participantLeaveEmailNotifications' => 'boolean',
        'employeeLeaveEmailNotifications' => 'boolean',
        'eventDeletedEmailNotifications' => 'boolean',
        'groupInvitationEmailNotifications' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
