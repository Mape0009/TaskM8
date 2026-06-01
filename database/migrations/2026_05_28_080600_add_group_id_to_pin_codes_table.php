<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pin_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('pin_codes', 'groupId')) {
                $table->unsignedBigInteger('groupId')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pin_codes', function (Blueprint $table) {
            if (Schema::hasColumn('pin_codes', 'groupId')) {
                $table->dropColumn('groupId');
            }
        });
    }
};
