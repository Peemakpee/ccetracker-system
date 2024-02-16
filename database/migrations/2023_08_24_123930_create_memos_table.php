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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'memo_to', length:2000);
            $table->string(column: 'memo_from', length:2000);
            $table->string(column: 'memo_subject', length:2000);
            $table->longText(column: 'memo_body')->nullable();
            $table->binary(column: 'e_signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
