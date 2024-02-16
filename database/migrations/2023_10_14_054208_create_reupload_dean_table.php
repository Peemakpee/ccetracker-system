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
        Schema::create('reupload_dean', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->string('file_path');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('date_uploadedByUser')->nullable();
            $table->string('deliverable_type')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->nullable();
            $table->text('firebase_url');
            $table->string('academic_year')->nullable();
            $table->string('program')->nullable();
            $table->string('user_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reupload_dean');
    }
};
