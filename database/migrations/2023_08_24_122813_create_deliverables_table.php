<?php

use App\Models\User;
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
        Schema::create('deliverables', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'deliv_type', length:2000);
            $table->string(column: 'file_name', length:2000);
            $table->string(column: 'file_path', length:2000);
            $table->string(column: 'file_type', length:2000);
            $table->integer(column: 'file_size')->nullable();
            $table->foreignIdFor(model: User::class, column: 'accessed_by')->nullable();
            $table->foreignIdFor(model: User::class, column: 'submitted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliverables');
    }
};
