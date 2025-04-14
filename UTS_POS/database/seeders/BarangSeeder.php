<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'id_kategori' => 1,
                'id_supplier' => 1,
                'kode_barang' => 'LX001',
                'nama_barang' => 'Mercedes-Benz S-Class',
                'stok' => 1,
                'harga' => 2500000000,
                'foto_barang' => 'merc-sclass.jpg'
            ],
            [
                'id_kategori' => 1,
                'id_supplier' => 2,
                'kode_barang' => 'LX002',
                'nama_barang' => 'BMW 7 Series',
                'stok' => 2,
                'harga' => 2300000000,
                'foto_barang' => 'bmw-7series.avif'
            ],
            [
                'id_kategori' => 2,
                'id_supplier' => 3,
                'kode_barang' => 'SP001',
                'nama_barang' => 'Porsche 911 GT3 RS',
                'stok' => 1,
                'harga' => 3500000000,
                'foto_barang' => 'porsche-911gt3rs.avif'
            ],
            [
                'id_kategori' => 2,
                'id_supplier' => 4,
                'kode_barang' => 'SP002',
                'nama_barang' => 'Lamborghini Huracán',
                'stok' => 1,
                'harga' => 4200000000,
                'foto_barang' => 'lambo-huracan.webp'
            ],
            [
                'id_kategori' => 3,
                'id_supplier' => 5,
                'kode_barang' => 'OR001',
                'nama_barang' => 'Jeep Wrangler Rubicon',
                'stok' => 3,
                'harga' => 1600000000,
                'foto_barang' => 'jeep-rubicon.avif'
            ],
            [
                'id_kategori' => 3,
                'id_supplier' => 6,
                'kode_barang' => 'OR002',
                'nama_barang' => 'Toyota Land Cruiser',
                'stok' => 3,
                'harga' => 1800000000,
                'foto_barang' => 'toyota-lc.jpeg'
            ],
        ]);
    }
}
