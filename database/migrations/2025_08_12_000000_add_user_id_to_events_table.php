<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('events', 'userId')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedBigInteger('userId')->nullable()->index()->after('id');
                // Optionally add FK if desired
                // $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'userId')) {
            Schema::table('events', function (Blueprint $table) {
                // $table->dropForeign(['userId']);
                $table->dropColumn('userId');
            });
        }
    }
};


