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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('subject');
            $table->string('body');
            $table->unsignedBigInteger('senderId');
            $table->unsignedBigInteger('recipientId');
            $table->dateTime('sentAt');
            $table->foreign('senderId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipientId')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['senderId', 'recipientId', 'sentAt'], 'mail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
