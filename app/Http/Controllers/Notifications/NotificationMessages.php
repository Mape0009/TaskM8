<?php

namespace App\Http\Controllers\Notifications;

class NotificationMessages
{
    public const NEW_TASK_ASSIGNED = 'You have been assigned a new task.';
    public const TASK_UPDATED = 'A task you are assigned to has been updated.';
    public const TASK_DELETED = 'A task you are assigned to has been deleted.';
    public const SHIFT_ASSIGNED = 'You have been assigned a new shift.';
    public const SHIFT_UPDATED = 'A shift you are assigned to has been updated.';
    public const SHIFT_DELETED = 'A shift you are assigned to has been deleted.';
    public const EVENT_UPDATED = 'An event you are participating in has been updated.';
    public const EVENT_DELETED = 'An event you are participating in has been deleted.';
    public const PARTICIPANT_LEFT = 'A participant has left an event you are managing.';
    public const PARTICIPANT_JOINED = 'A new participant has joined an event you are managing.';
    public const GROUP_JOINED = 'A member has joined a group you are part of.';
    public const GROUP_LEFT = 'A member has left a group you are part of.';
}