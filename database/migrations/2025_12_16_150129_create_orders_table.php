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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang pesan
        $table->foreignId('tambal_ban_id')->constrained()->onDelete('cascade'); // Bengkel mana
        $table->string('nama_pemesan');
        $table->string('nomer_telepon');
        $table->text('alamat_lengkap'); // Lokasi user
        $table->enum('jenis_kendaraan', ['motor', 'mobil']);
        $table->text('keluhan')->nullable(); // Misal: Ban bocor halus / robek
        $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
