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
        Schema::create('reuploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->text('comment')->nullable();
            $table->string('file_path');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('date_uploadedByUser');
            $table->string('deliverable_type');
            $table->string('file_name');
            $table->string('status');
            $table->text('firebaseUrl_wSign')->nullable();
            $table->string('academic_year');
            $table->string('program');
            $table->string('user_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuploads');
    }
};
