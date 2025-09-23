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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('isRecurring')->default(false)->after('participantLimit');
            $table->string('recurrenceInterval')->nullable()->after('isRecurring'); // daily, weekly, monthly, yearly, custom
            $table->string('recurrenceCustom')->nullable()->after('recurrenceInterval'); // free-text custom description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['isRecurring', 'recurrenceInterval', 'recurrenceCustom']);
        });
    }
};


