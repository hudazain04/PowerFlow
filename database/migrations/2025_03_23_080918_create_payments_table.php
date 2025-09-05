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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->integer('amount');
            $table->integer('current_spending');
            $table->integer('next_spending')->nullable();
            $table->foreignId('counter_id')->constrained('counters')->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->enum('status',array_values((new \ReflectionClass(\App\Types\PaymentStatus::class))->getConstants()));
            $table->enum('type',array_values((new \ReflectionClass(\App\Types\PaymentType::class))->getConstants()))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
