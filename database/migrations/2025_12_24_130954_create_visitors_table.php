<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date')->unique(); // Tanggal unik
            $table->unsignedBigInteger('count')->default(0); // Jumlah pengunjung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
