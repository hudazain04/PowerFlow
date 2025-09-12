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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->enum('type',array_values((new ReflectionClass(\App\Types\ActionTypes::class))->getConstants()));
            $table->enum('status',array_values((new ReflectionClass(\App\Types\ComplaintStatusTypes::class))->getConstants()));
            $table->foreignId('counter_id')->nullable()->constrained('counters')->nullOnDelete();
            $table->foreignId('generator_id')->nullable()->constrained('powerGenerators')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('actions')->nullOnDelete();
            $table->integer('priority')->nullable();
            $table->json('relatedData')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
