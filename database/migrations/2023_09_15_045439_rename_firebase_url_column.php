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
    public function up()
    {
        DB::statement('ALTER TABLE approved_ph CHANGE COLUMN firebase_url firebaseUrl_wSign VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('ALTER TABLE approved_ph CHANGE COLUMN firebaseUrl_wSign firebase_url VARCHAR(255)');
    }
};
