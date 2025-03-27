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
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1, // Elektronik
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Laptop Gaming',
                'harga_beli' => 10000000,
                'harga_jual' => 12000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1, // Elektronik
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Smartphone 5G',
                'harga_beli' => 5000000,
                'harga_jual' => 6000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2, // Makanan
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Cokelat Kemasan',
                'harga_beli' => 30000,
                'harga_jual' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 3, // Minuman
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Air Mineral Botol',
                'harga_beli' => 2000,
                'harga_jual' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 4, // Fashion
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Kaos Polos',
                'harga_beli' => 45000,
                'harga_jual' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 5, // Kesehatan
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Masker Medis',
                'harga_beli' => 5000,
                'harga_jual' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 1, // Elektronik
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Mouse Wireless',
                'harga_beli' => 80000,
                'harga_jual' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3, // Minuman
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Kopi Instan',
                'harga_beli' => 12000,
                'harga_jual' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 4, // Fashion
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Sneakers Putih',
                'harga_beli' => 200000,
                'harga_jual' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 4, // Fashion
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Jam Tangan Sport',
                'harga_beli' => 300000,
                'harga_jual' => 450000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
