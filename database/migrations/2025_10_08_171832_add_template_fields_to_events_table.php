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
            $table->boolean('is_template')->default(false);
            $table->unsignedBigInteger('template_from_event_id')->nullable();
            $table->unsignedBigInteger('template_from_group_id')->nullable();
            
            $table->foreign('template_from_event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('template_from_group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['template_from_event_id']);
            $table->dropForeign(['template_from_group_id']);
            $table->dropColumn(['is_template', 'template_from_event_id', 'template_from_group_id']);
        });
    }
};

            $table->dropForeign(['template_from_event_id']);
            $table->dropForeign(['template_from_group_id']);
            $table->dropColumn(['is_template', 'template_from_event_id', 'template_from_group_id']);
        });
    }
};
