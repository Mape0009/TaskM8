<?php

namespace App\Http\Controllers\Notifications;

class NotificationMessages
{
    public const NEW_TASK_ASSIGNED = 'Du er blevet tildelt en ny opgave.';
    public const TASK_UPDATED = 'En opgave du er tildelt, er blevet opdateret.';
    public const TASK_DELETED = 'En opgave du er tildelt, er blevet slettet.';
    public const SHIFT_ASSIGNED = 'Du er blevet tildelt en ny vagt.';
    public const SHIFT_UPDATED = 'En vagt du er tildelt, er blevet opdateret.';
    public const SHIFT_DELETED = 'En vagt du er tildelt, er blevet slettet.';
    public const EVENT_UPDATED = 'En begivenhed du deltager i, er blevet opdateret.';
    public const EVENT_DELETED = 'En begivenhed du deltager i, er blevet slettet.';
    public const EVENT_INVITED = 'Du er blevet inviteret til en begivenhed.';
    public const PARTICIPANT_LEFT = 'En deltager har forladt en begivenhed du administrerer.';
    public const PARTICIPANT_JOINED = 'En ny deltager har sluttede sig til en begivenhed du administrerer.';
    public const GROUP_INVITED = 'Du er blevet inviteret til en gruppe.';
    public const GROUP_JOINED = 'Et medlem har sluttet sig til en gruppe du administrerer.';
    public const GROUP_LEFT = 'Et medlem har forladt en gruppe du administrerer.';
}