<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('type', 50); // medical, personal, work, other
            $table->string('location')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->integer('duration')->default(30); // minutos
            $table->string('doctor_name')->nullable();
            $table->string('specialty')->nullable();
            $table->string('status', 20)->default('scheduled'); // scheduled, completed, cancelled, rescheduled
            $table->boolean('reminder_enabled')->default(true);
            $table->integer('reminder_minutes')->default(60); // minutos antes
            $table->text('notes')->nullable();
            $table->string('color', 7)->default('#0d6efd'); // color para el calendario
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'appointment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
