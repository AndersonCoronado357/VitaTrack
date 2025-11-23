<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_metric_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('metric_type', 50);
            $table->decimal('min_normal', 10, 2)->nullable();
            $table->decimal('max_normal', 10, 2)->nullable();
            $table->decimal('min_warning', 10, 2)->nullable();
            $table->decimal('max_warning', 10, 2)->nullable();
            $table->decimal('min_normal_secondary', 10, 2)->nullable(); // para presión diastólica
            $table->decimal('max_normal_secondary', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'metric_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_metric_ranges');
    }
};
