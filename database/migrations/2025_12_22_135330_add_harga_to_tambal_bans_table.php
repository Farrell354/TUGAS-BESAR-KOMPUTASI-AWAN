<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tambal_bans', function (Blueprint $table) {
            $table->integer('harga_motor_dekat')->nullable();
            $table->integer('harga_motor_jauh')->nullable();
            $table->integer('harga_mobil_dekat')->nullable();
            $table->integer('harga_mobil_jauh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tambal_bans', function (Blueprint $table) {
            //
        });
    }
};
