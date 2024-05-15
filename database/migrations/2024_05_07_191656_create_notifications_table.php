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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('message', 120);
            $table->string('subtitle', 120);
            $table->string('title', 120);
            $table->string('main_text');
            $table->boolean('send')->default(false);
            $table->timestamp('date_start_at');
            $table->timestamp('date_end_at');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('notifications');
            $table->foreign('type_id')->references('id')->on('notifications_type');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
