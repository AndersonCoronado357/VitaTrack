<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrition_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->integer('daily_calories_goal')->default(2000);
            $table->decimal('daily_proteins_goal', 8, 2)->default(50); // gramos
            $table->decimal('daily_carbs_goal', 8, 2)->default(250); // gramos
            $table->decimal('daily_fats_goal', 8, 2)->default(70); // gramos
            $table->decimal('daily_fiber_goal', 8, 2)->default(25)->nullable(); // gramos
            $table->decimal('daily_water_goal', 8, 2)->default(2000)->nullable(); // ml
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_goals');
    }
};
