<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('metric_type', 50); // blood_pressure, glucose, weight, heart_rate, temperature, oxygen, cholesterol
            $table->decimal('value', 10, 2); // valor principal
            $table->decimal('value_secondary', 10, 2)->nullable(); // para presión arterial (sistólica/diastólica)
            $table->string('unit', 20); // mmHg, mg/dL, kg, bpm, °C, %, mg/dL
            $table->date('measured_date');
            $table->time('measured_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_fasting')->default(false)->nullable(); // para glucosa
            $table->string('status', 20)->default('normal'); // normal, warning, alert
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'metric_type', 'measured_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_metrics');
    }
};
