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
        Schema::table('pin_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('pin_codes', 'eventId')) {
                $table->unsignedBigInteger('eventId')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pin_codes', function (Blueprint $table) {
            if (Schema::hasColumn('pin_codes', 'eventId')) {
                $table->dropColumn('eventId');
            }
        });
    }
};


