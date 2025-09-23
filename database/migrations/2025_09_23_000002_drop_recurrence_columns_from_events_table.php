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
            $columns = collect(['isRecurring', 'recurrenceInterval', 'recurrenceCustom'])
                ->filter(fn ($col) => Schema::hasColumn('events', $col))
                ->values();

            if ($columns->isNotEmpty()) {
                foreach ($columns as $col) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'isRecurring')) {
                $table->boolean('isRecurring')->default(false)->after('participantLimit');
            }
            if (!Schema::hasColumn('events', 'recurrenceInterval')) {
                $table->string('recurrenceInterval')->nullable()->after('isRecurring');
            }
            if (!Schema::hasColumn('events', 'recurrenceCustom')) {
                $table->string('recurrenceCustom')->nullable()->after('recurrenceInterval');
            }
        });
    }
};




