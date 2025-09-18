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
        Schema::create('generator_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generator_id')->constrained('power_generators')->cascadeOnDelete();
            $table->enum('spendingType',array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()));
            $table->integer('kiloPrice');
            $table->integer('afterPaymentFrequency');
            $table->enum('day',array_values((new ReflectionClass(\App\Types\DaysOfWeek::class))->getConstants()));
            $table->date('nextDueDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generator_settings');
    }
};
