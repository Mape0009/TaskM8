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
        if (!Schema::hasColumn('events', 'participantLimit')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedInteger('participantLimit')->nullable()->after('ownerId');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('events', 'participantLimit')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('participantLimit');
            });
        }
    }
};


