<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sleep_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('sleep_date'); // fecha de inicio del sueño
            $table->time('bedtime'); // hora de acostarse
            $table->time('wake_time'); // hora de despertar
            $table->decimal('total_hours', 4, 2); // horas totales de sueño
            $table->integer('interruptions')->default(0); // veces que se despertó
            $table->string('quality', 20); // excellent, good, fair, poor
            $table->boolean('felt_rested')->default(false); // se sintió descansado
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'sleep_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sleep_records');
    }
};
