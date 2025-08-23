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
        Schema::create('electrical_boxes', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('location');
            $table->float('latitude');
            $table->float('longitude');
            $table->integer('capacity');
            $table->foreignId('generator_id')->constrained('power_generators')->cascadeOnDelete();
            $table->json('translation')->nullable();
//            $table->geometry('dd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electrical_boxes');
    }
};
