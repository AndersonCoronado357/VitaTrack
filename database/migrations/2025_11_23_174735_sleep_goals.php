<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleep_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('target_hours', 4, 2)->default(8.0); // meta de horas
            $table->time('target_bedtime')->nullable(); // hora ideal de acostarse
            $table->time('target_wake_time')->nullable(); // hora ideal de despertar
            $table->integer('max_interruptions')->default(2); // máximo de interrupciones aceptables
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleep_goals');
    }
};
