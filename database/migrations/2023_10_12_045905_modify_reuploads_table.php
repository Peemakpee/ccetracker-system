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
        Schema::table('reuploads', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->string('academic_year')->nullable()->change();
            $table->string('program')->nullable()->change();
            $table->string('deliverable_type')->nullable()->change();
            $table->string('file_name')->nullable()->change();
            $table->timestamp('date_uploadedByUser')->nullable()->change();
            $table->string('firebase_url')->nullable(false)->change();
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reuploads', function (Blueprint $table) {
            $table->string('status')->nullable(false)->change();
            $table->string('academic_year')->nullable(false)->change();
            $table->string('program')->nullable(false)->change();
            $table->string('deliverable_type')->nullable(false)->change();
            $table->string('file_name')->nullable(false)->change();
            $table->timestamp('date_uploadedByUser')->nullable(false)->change();
            $table->string('firebase_url')->nullable()->change();
            $table->timestamp('created_at')->nullable(false)->change();
            $table->timestamp('updated_at')->nullable(false)->change();
        });
    }
};
