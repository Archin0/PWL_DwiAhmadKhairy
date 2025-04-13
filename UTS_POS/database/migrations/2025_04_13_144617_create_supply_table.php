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
        Schema::create('supply', function (Blueprint $table) {
            $table->id('id_supply');
            $table->unsignedBigInteger('id_supplier')->index();
            $table->unsignedBigInteger('id_barang')->index();
            $table->unsignedBigInteger('id_user')->index();
            $table->integer('jumlah');
            $table->bigInteger('harga_beli');
            $table->timestamps();

            $table->foreign('id_supplier')->references('id_supplier')->on('supplier');
            $table->foreign('id_barang')->references('id_barang')->on('barang');
            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply');
    }
};
