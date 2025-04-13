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
            $table->unsignedBigInteger('id_barang')->index();
            $table->string('kode_transaksi')->unique();
            $table->integer('jumlah');
            $table->integer('total_barang');
            $table->bigInteger('subtotal');
            $table->integer('diskon')->default(0);
            $table->bigInteger('total_harga');
            $table->bigInteger('bayar');
            $table->bigInteger('kembali');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
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
