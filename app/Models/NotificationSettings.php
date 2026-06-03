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
        'eventInvitationSystemNotifications',
        'groupInvitationSystemNotifications',
        'newEventEmailNotifications',
        'newShiftEmailNotifications',
        'participantLeaveEmailNotifications',
        'employeeLeaveEmailNotifications',
        'eventDeletedEmailNotifications',
        'eventInvitationEmailNotifications',
        'groupInvitationEmailNotifications',
    ];

    protected $casts = [
        'newEventSystemNotifications' => 'boolean',
        'newShiftSystemNotifications' => 'boolean',
        'participantLeaveSystemNotifications' => 'boolean',
        'employeeLeaveSystemNotifications' => 'boolean',
        'eventDeletedSystemNotifications' => 'boolean',
        'eventInvitationSystemNotifications' => 'boolean',
        'groupInvitationSystemNotifications' => 'boolean',
        'newEventEmailNotifications' => 'boolean',
        'newShiftEmailNotifications' => 'boolean',
        'participantLeaveEmailNotifications' => 'boolean',
        'employeeLeaveEmailNotifications' => 'boolean',
        'eventDeletedEmailNotifications' => 'boolean',
        'eventInvitationEmailNotifications' => 'boolean',
        'groupInvitationEmailNotifications' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
