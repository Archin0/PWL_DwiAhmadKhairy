<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) { // 10 transaksi
            for ($j = 1; $j <= 3; $j++) { // 3 barang per transaksi
                $harga = rand(5000, 500000); // Harga acak untuk tiap barang
                $jumlah = rand(1, 5); // Jumlah barang acak

                DB::table('t_penjualan_detail')->insert([
                    'detail_id' => ($i - 1) * 3 + $j, // ID unik untuk detail
                    'penjualan_id' => $i, // Referensi ke t_penjualan
                    'barang_id' => rand(1, 10), // Barang acak
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
