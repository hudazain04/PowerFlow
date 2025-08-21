<?php

use App\Types\ComplaintStatusTypes;
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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('type',array_values((new ReflectionClass(\App\Types\ComplaintTypes::class))->getConstants()));
            $table->enum('status',array_values((new \ReflectionClass(ComplaintStatusTypes::class))->getConstants()));
            $table->foreignId('counter_id')->nullable()->constrained('counters')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('translation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
