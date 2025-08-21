<?php

use App\Types\SubscriptionTypes;
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
        Schema::create('subscription_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('period');
            $table->enum('type',array_values((new \ReflectionClass(SubscriptionTypes::class))->getConstants()));
            $table->string('location');
            $table->string('name');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('planPrice_id')->nullable()->constrained('plan_prices')->nullOnDelete();
            $table->enum('status',array_values((new ReflectionClass(\App\Types\GeneratorRequests::class))->getConstants()));
            $table->json('translation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_requests');
    }
};
