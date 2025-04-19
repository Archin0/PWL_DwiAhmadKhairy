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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_barang')->index();
            $table->unsignedBigInteger('id_transaksi')->index();
            $table->integer('jumlah');
            $table->bigInteger('subtotal');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi');
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
