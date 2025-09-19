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
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('physical_device_id')->unique()->nullable();
            $table->string('QRCode')->nullable();
            $table->integer('current_spending');
            $table->enum('spendingType',array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()));
            $table->foreignId('generator_id')->nullable()->constrained('power_generators')->nullOnDelete();
            $table->string('status')->default(\App\Types\CounterStatus::InCheck);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
