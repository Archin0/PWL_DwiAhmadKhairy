<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['kode_kategori' => 'LX', 'nama_kategori' => 'Luxury', 'deskripsi' => 'Mobil mewah dengan fitur premium, kenyamanan tingkat tinggi, dan desain elegan.'],
            ['kode_kategori' => 'SP', 'nama_kategori' => 'Sports', 'deskripsi' => 'Mobil dengan performa tinggi, akselerasi cepat, dan desain aerodinamis.'],
            ['kode_kategori' => 'OR', 'nama_kategori' => 'Off-Road', 'deskripsi' => 'Mobil tangguh yang dirancang untuk medan berat seperti gunung, hutan, dan gurun.'],
        ]);
    }
}
