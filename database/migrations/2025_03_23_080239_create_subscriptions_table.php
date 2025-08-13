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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->date('start_time');
            $table->integer('period');
            $table->integer('price');
            $table->foreignId('planPrice_id')->nullable()->constrained('plan_prices')->nullOnDelete();
            $table->foreignId('generator_id')->nullable()->constrained('power_generators')->nullOnDelete();
            $table->boolean('expired_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
