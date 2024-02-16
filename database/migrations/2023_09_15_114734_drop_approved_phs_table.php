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
        Schema::rename('approve_phs', 'approve_ph');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
