<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tambal_bans', function (Blueprint $table) {
            // Cek dulu, kalau kolom BELUM ada, baru buat
            if (!Schema::hasColumn('tambal_bans', 'harga_motor_dekat')) {
                $table->integer('harga_motor_dekat')->nullable();
            }
            if (!Schema::hasColumn('tambal_bans', 'harga_motor_jauh')) {
                $table->integer('harga_motor_jauh')->nullable();
            }
             if (!Schema::hasColumn('tambal_bans', 'harga_mobil_dekat')) {
                $table->integer('harga_mobil_dekat')->nullable();
            }
             if (!Schema::hasColumn('tambal_bans', 'harga_mobil_jauh')) {
                $table->integer('harga_mobil_jauh')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('tambal_bans', function (Blueprint $table) {
            $table->dropColumn([
                'harga_motor_dekat', 
                'harga_motor_jauh',
                'harga_mobil_dekat',
                'harga_mobil_jauh'
            ]);
        });
    }
};