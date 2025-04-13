<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AksesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('akses')->insert([
            ['id_user' => 1, 'kelola_akun' => true, 'kelola_barang' => true, 'transaksi' => true, 'laporan' => true],
            ['id_user' => 2, 'kelola_akun' => false, 'kelola_barang' => false, 'transaksi' => true, 'laporan' => false],
        ]);
    }
}
