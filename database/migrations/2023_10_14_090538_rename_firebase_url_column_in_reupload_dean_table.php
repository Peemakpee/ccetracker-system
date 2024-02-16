<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reupload_dean', function (Blueprint $table) {
            // Add a new column with the desired name
            $table->string('firebaseUrl_wSign')->after('firebase_url');
        });

        // Copy data from the old column to the new one
        DB::table('reupload_dean')->update(['firebaseUrl_wSign' => DB::raw('firebase_url')]);

        Schema::table('reupload_dean', function (Blueprint $table) {
            // Drop the old column
            $table->dropColumn('firebase_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reupload_dean', function (Blueprint $table) {
            // Add the old column back with the original name
            $table->string('firebase_url')->after('firebaseUrl_wSign');
        });

        // Copy data from the new column to the old one
        DB::table('reupload_dean')->update(['firebase_url' => DB::raw('firebaseUrl_wSign')]);

        Schema::table('reupload_dean', function (Blueprint $table) {
            // Drop the new column
            $table->dropColumn('firebaseUrl_wSign');
        });
    }
};
