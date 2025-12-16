<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('tambal_bans', function (Blueprint $table) {
        $table->id();
        
        // INI YANG KURANG (Relasi ke Owner)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); 
        
        // Data Dasar
        $table->string('nama_bengkel');
        $table->string('gambar')->nullable();
        $table->enum('kategori', ['motor', 'mobil', 'keduanya'])->default('motor');
        
        // Kontak & Alamat (Gabungan dari file add_nomer_telepon & add_details lama)
        $table->string('alamat')->nullable();
        $table->string('nomer_telepon'); // <--- Penting
        
        // Jam Operasional
        $table->time('jam_buka')->nullable();
        $table->time('jam_tutup')->nullable();
        
        // Koordinat (Gabungan dari file add_coordinates lama)
        $table->string('latitude');
        $table->string('longitude');
        
        // Deskripsi
        $table->text('deskripsi')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambal_bans');
    }
};
