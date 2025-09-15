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
        Schema::create('task_participants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('taskId');
            $table->unsignedBigInteger('userId');
            $table->foreign('taskId')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['taskId', 'userId'], 'task_participant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_participants');
    }
};
