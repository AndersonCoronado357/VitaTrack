<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habit_id');
            $table->unsignedBigInteger('user_id');
            $table->date('completion_date');
            $table->integer('count')->default(1); // cuántas veces se completó ese día
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Evitar duplicados por día
            $table->unique(['habit_id', 'completion_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};
