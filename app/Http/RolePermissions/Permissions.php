<?php

namespace App\Http\RolePermissions;

class Permissions
{
    public const OWNER = [
            'delete-event',
            'edit-event',
            'view-event',
            'create-task',
            'edit-task',
            'delete-task',
            'assign-task',
            'view-task',
            'create-shift',
            'edit-shift',
            'delete-shift',
            'view-shift',
            'manage-invites',
            'view-participants',
            'manage-participants',
            'manage-taskWorkers',
            'manage-taskManagers',
            'manage-coOwners',
            'manage-volunteers',
            'delete-participant',
            'transfer-ownership',
        ];

        public const CO_OWNER = [
            'edit-event',
            'view-event',
            'create-task',
            'edit-task',
            'delete-task',
            'assign-task',
            'view-task',
            'create-shift',
            'edit-shift',
            'delete-shift',
            'view-shift',
            'manage-invites',
            'view-participants',
            'manage-participants',
            'manage-taskWorkers',
            'manage-taskManagers',
            'manage-volunteers',
            'delete-participant',
            'respond-event',
        ];

        public const TASK_MANAGER = [
            'view-event',
            'create-task',
            'edit-task',
            'assign-task',
            'view-task',
            'create-shift',
            'edit-shift',
            'delete-shift',
            'view-shift',
            'view-participants',
            'respond-event',
        ];

        public const TASK_WORKER = [
            'view-event',
            'view-task',
            'view-shift',
            'view-participants',
            'respond-event',
        ];

        public const VOLUNTEER = [
            'view-event',
            'view-task',
            'view-shift',
            'view-participants',
            'respond-event',
            'unvolunteer',
        ];

        public const PARTICIPANT = [
            'view-event',
            'view-participants',
            'respond-event',
            'volunteer',
        ];

        public static function forRole(string $role): array
        {
            return match ($role) {
                'owner' => self::OWNER,
                'coOwner' => self::CO_OWNER,
                'taskManager' => self::TASK_MANAGER,
                'taskWorker' => self::TASK_WORKER,
                'volunteer' => self::VOLUNTEER,
                'participant' => self::PARTICIPANT,
                default => [],
            };
        }

        public static function hasPermission(string $role, string $permission): bool
        {
            return in_array($permission, self::forRole($role));
        }
}