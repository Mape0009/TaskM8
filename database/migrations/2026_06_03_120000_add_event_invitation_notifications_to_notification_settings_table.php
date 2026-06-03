<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table): void {
            $table->boolean('eventInvitationSystemNotifications')->default(false)->after('eventDeletedSystemNotifications');
            $table->boolean('eventInvitationEmailNotifications')->default(false)->after('eventDeletedEmailNotifications');
        });
    }

    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table): void {
            $table->dropColumn(['eventInvitationSystemNotifications', 'eventInvitationEmailNotifications']);
        });
    }
};