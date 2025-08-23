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
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->integer('amount');
            $table->enum('status',array_values((new \ReflectionClass(\App\Types\PaymentStatus::class))->getConstants()));
            $table->enum('type',array_values((new \ReflectionClass(\App\Types\PaymentType::class))->getConstants()))->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->foreignId('subscriptionRequest_id')->nullable()->constrained('subscription_requests')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
