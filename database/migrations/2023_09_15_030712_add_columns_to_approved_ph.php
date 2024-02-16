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
        Schema::table('approved_ph', function (Blueprint $table) {

            $table->timestamp('date_upladedByUser')->nullable();
            $table->string('deliverable_type')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->nullable();
            $table->string('firebase_url')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('program')->nullable();

       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approved_ph', function (Blueprint $table) {
            $table->dropColumn([
                'date_upladedByUser',
                'deliverable_type',
                'file_name',
                'status',
                'firebase_url',
                'academic_year',
                'program',
            ]);
        });
    }
};
