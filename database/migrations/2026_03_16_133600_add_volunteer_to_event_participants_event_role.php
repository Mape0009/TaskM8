<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `event_participants`
            MODIFY `eventRole` ENUM('owner','coOwner','taskManager','taskWorker','volunteer','participant')
            NOT NULL
            DEFAULT 'participant'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE `event_participants`
            MODIFY `eventRole` ENUM('owner','coOwner','taskManager','taskWorker','volunteer','participant')
            NOT NULL
            DEFAULT 'participant'
        ");
    }
};

