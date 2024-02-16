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
        Schema::table('approve_ph', function (Blueprint $table) {
            Schema::table('approve_ph', function (Blueprint $table) {
                $table->string('user_name'); // Add the user_name column
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approve_ph', function (Blueprint $table) {
            $table->dropColumn('user_name'); // Remove the user_name column if needed
        });
    }
};
