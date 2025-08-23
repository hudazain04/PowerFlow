<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Types\GeneratorRequests;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('box_id')->constrained('electrical_boxes')->cascadeOnDelete();
            $table->foreignId('generator_id')->constrained('power_generators')->cascadeOnDelete();
            $table->enum('status',array_values((new ReflectionClass(\App\Types\GeneratorRequests::class))->getConstants()))->default(GeneratorRequests::PENDING);
            $table->enum('spendingType',array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()));
            $table->text('user_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('translation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_requests');
    }
};
