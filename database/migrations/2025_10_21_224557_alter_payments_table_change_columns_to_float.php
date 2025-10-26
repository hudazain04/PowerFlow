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
        Schema::table('payments', function (Blueprint $table) {
            $table->float('amount')->change();
            $table->float('current_spending')->change();
            $table->float('next_spending')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('amount')->change();
            $table->integer('current_spending')->change();
            $table->integer('next_spending')->nullable()->change();
        });
    }
};
