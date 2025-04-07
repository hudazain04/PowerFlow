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
        Schema::create('counter__boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_id')->constrained('counters')->cascadeOnDelete();
            $table->foreignId('box_id')->constrained('electrical_boxes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counter__boxes');
    }
};
