<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_stok')->insert([
                'stok_id' => $i,
                'barang_id' => $i, // Menghubungkan dengan barang_id yang ada
                'user_id' => 1, // Asumsikan admin yang menginput stok
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(10, 100), // Stok acak antara 10 hingga 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
