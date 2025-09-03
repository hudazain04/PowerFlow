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
        Schema::table('counters', function (Blueprint $table) {
            if (!Schema::hasColumn('counters', 'generator_id')) {
                $table->unsignedBigInteger('generator_id')->nullable();
            }

            if (!Schema::hasColumn('counters', 'status')) {
                $table->string('status')->default(\App\Types\CounterStatus::InCheck);
            }
        });
        $generatorId = \App\Models\PowerGenerator::first()->id ?? null;

        if ($generatorId) {
            \Illuminate\Support\Facades\DB::table('counters')->update(['generator_id' => $generatorId]);
        }
        Schema::table('counters', function (Blueprint $table) {
            $table->foreign('generator_id')
                ->references('id')
                ->on('power_generators')
                ->cascadeOnDelete();

            // Make the column not nullable if needed
            $table->unsignedBigInteger('generator_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropForeign(['generator_id']);
            $table->dropColumn(['status','generator_id']);
        });
    }
};
