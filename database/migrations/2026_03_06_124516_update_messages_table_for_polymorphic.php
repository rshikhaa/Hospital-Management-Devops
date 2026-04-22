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
        Schema::table('messages', function (Blueprint $table) {
             $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);

            // Drop old columns
            $table->dropColumn(['sender_id', 'receiver_id']);

            // Add polymorphic columns
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type');
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop polymorphic columns
            $table->dropColumn(['sender_id', 'sender_type', 'receiver_id', 'receiver_type']);

            // Recreate original columns (patients only)
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');

            $table->foreign('sender_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }
};
