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
        Schema::create('akses', function (Blueprint $table) {
            $table->id('id_akses');
            $table->unsignedBigInteger('id_user')->index();
            $table->boolean('kelola_akun')->default(false);
            $table->boolean('kelola_barang')->default(false);
            $table->boolean('transaksi')->default(true);
            $table->boolean('laporan')->default(false);
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akses');
    }
};
