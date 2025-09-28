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
        Schema::create('consumption_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_id')->constrained('counters')->cascadeOnDelete();
            $table->decimal('q1', 10, 2)->nullable();
            $table->decimal('q3', 10, 2)->nullable();
            $table->decimal('iqr', 10, 2)->nullable();
            $table->decimal('upper_bound', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_statistics');
    }
};
