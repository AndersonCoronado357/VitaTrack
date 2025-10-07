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
        Schema::create('Medications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('dosage', 150);
            $table->string('frequency', 150);
            $table->string('time', 150);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('administration_route', 100);
            $table->boolean('reminder')->default(true);
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Medications');
    }
};
