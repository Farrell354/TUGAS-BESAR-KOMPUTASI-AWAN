<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tambal_bans', function (Blueprint $table) {
            // Kita set default value agar data lama tidak error
            $table->integer('harga_motor_dekat')->default(20000);
            $table->integer('harga_motor_jauh')->default(35000);
            $table->integer('harga_mobil_dekat')->default(35000);
            $table->integer('harga_mobil_jauh')->default(50000);
        });
    }

    public function down(): void
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
