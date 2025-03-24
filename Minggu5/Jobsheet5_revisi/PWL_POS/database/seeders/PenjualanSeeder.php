<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_penjualan')->insert([
                'penjualan_id' => $i,
                'user_id' => 2, // Asumsikan kasir yang melakukan transaksi
                'pembeli' => 'Pelanggan ' . $i, // Nama pembeli dibuat dummy
                'penjualan_kode' => 'TRX' . Str::padLeft($i, 5, '0'), // Kode transaksi unik
                'penjualan_tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
