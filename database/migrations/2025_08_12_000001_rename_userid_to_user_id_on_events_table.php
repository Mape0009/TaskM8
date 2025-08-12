<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'userId') && !Schema::hasColumn('events', 'user_id')) {
                $table->renameColumn('userId', 'user_id');
            } elseif (!Schema::hasColumn('events', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'user_id') && !Schema::hasColumn('events', 'userId')) {
                $table->renameColumn('user_id', 'userId');
            }
        });
    }
};


