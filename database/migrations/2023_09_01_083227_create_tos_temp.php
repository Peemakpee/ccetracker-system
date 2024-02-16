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
        Schema::create('tos_temp', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'deliv_type', length:2000);
            $table->string(column: 'file_name', length:2000);
            $table->string(column: 'file_path', length:2000);
            $table->string(column: 'file_type', length:2000);
            $table->integer(column: 'file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tos_temp');
    }
};
