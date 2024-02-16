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
        Schema::create('tbm_dean', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('date_upladedByUser')->nullable();
            $table->string('deliverable_type')->nullable();
            $table->string('file_path')->nullable();
            $table->string('comment')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->nullable();
            $table->string('firebaseUrl_wSignDean', 1000);
            $table->string('academic_year')->nullable();
            $table->string('program')->nullable();
            $table->string('user_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbm_dean');
    }
};
