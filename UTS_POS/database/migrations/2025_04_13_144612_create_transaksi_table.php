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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user')->index();
            $table->string('kode_transaksi')->unique();
            $table->string('pembeli');
            $table->integer('total_barang');
            $table->integer('diskon')->default(0);
            $table->bigInteger('total_harga');
            $table->enum('metode_pembayaran', ['Card Only', 'Transfer']);
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
