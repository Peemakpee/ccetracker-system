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
        Schema::create('archive_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); // Reference to the original upload
            $table->string('status'); // Include the status column to indicate the source
            $table->string('file_name'); // Include the file name
            $table->string('firebase_url'); // Include the Firebase URL
            $table->string('program'); // Include the program
            $table->unsignedBigInteger('user_id'); // Include the user_id
            $table->string('user_name'); // Include the user_name
            $table->string('academic_year'); // Include the academic_year
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_uploads');
    }
};
