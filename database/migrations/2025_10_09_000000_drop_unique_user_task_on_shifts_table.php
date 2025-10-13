<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure single-column indexes exist so MySQL doesn't depend on the composite unique index
        Schema::table('shifts', function (Blueprint $table) {
            try { $table->index('taskId', 'shifts_taskId_idx'); } catch (\Throwable $e) { /* already exists */ }
            try { $table->index('userId', 'shifts_userId_idx'); } catch (\Throwable $e) { /* already exists */ }
        });

        // Now drop the composite unique index
        Schema::table('shifts', function (Blueprint $table) {
            try {
                $table->dropUnique('shift_unique');
            } catch (\Throwable $e) {
                try {
                    $table->dropUnique(['taskId', 'userId']);
                } catch (\Throwable $e2) {
                    // ignore if not present
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // Restore the unique constraint if rolling back
            try {
                $table->unique(['taskId', 'userId'], 'shift_unique');
            } catch (\Throwable $e) {
                // ignore
            }
            // Optionally drop the helper indexes
            try { $table->dropIndex('shifts_taskId_idx'); } catch (\Throwable $e) { /* ignore */ }
            try { $table->dropIndex('shifts_userId_idx'); } catch (\Throwable $e) { /* ignore */ }
        });
    }
};


