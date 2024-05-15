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
        Schema::create('additions_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('addition_id');
            $table->timestamps();

            $table->primary(['notification_id', 'addition_id']);

            $table->foreign('notification_id')->references('id')->on('notifications');
            $table->foreign('addition_id')->references('id')->on('notification_additions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additions_notifications');
    }
};
