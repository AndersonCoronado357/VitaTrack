<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('meal_type', 50); // breakfast, lunch, dinner, snack
            $table->string('food_name', 200);
            $table->text('description')->nullable();
            $table->decimal('quantity', 8, 2); // cantidad en gramos o ml
            $table->string('unit', 20)->default('g'); // g, ml, unidad, porción
            $table->integer('calories')->default(0);
            $table->decimal('proteins', 8, 2)->default(0); // gramos
            $table->decimal('carbs', 8, 2)->default(0); // gramos
            $table->decimal('fats', 8, 2)->default(0); // gramos
            $table->decimal('fiber', 8, 2)->default(0)->nullable(); // gramos
            $table->date('meal_date');
            $table->time('meal_time')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'meal_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
