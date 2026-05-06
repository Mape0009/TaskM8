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
        DB::statement("
            ALTER TABLE `shifts`
            ADD COLUMN `status` ENUM('pending', 'accepted', 'in progress') NOT NULL DEFAULT 'pending' AFTER `userId`
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE `shifts`
            DROP COLUMN `status`
        ");
    }
};
