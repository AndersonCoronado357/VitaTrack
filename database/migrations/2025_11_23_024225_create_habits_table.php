<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('frequency', 50); // daily, weekly, monthly
            $table->integer('goal_count')->default(1); // cuántas veces por día/semana/mes
            $table->time('reminder_time')->nullable();
            $table->string('color', 7)->default('#0d6efd'); // color hex para UI
            $table->string('icon', 50)->default('bi-check-circle'); // clase de bootstrap icon
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
