<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('userId')->unique();
            $table->boolean('newEventSystemNotifications')->default(false);
            $table->boolean('newShiftSystemNotifications')->default(false);
            $table->boolean('participantLeaveSystemNotifications')->default(false);
            $table->boolean('employeeLeaveSystemNotifications')->default(false);
            $table->boolean('eventDeletedSystemNotifications')->default(false);
            $table->boolean('groupInvitationSystemNotifications')->default(false);
            $table->boolean('newEventEmailNotifications')->default(false);
            $table->boolean('newShiftEmailNotifications')->default(false);
            $table->boolean('participantLeaveEmailNotifications')->default(false);
            $table->boolean('employeeLeaveEmailNotifications')->default(false);
            $table->boolean('eventDeletedEmailNotifications')->default(false);
            $table->boolean('groupInvitationEmailNotifications')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
