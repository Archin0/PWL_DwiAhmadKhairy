<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('toko')->insert([
            ['nama_toko' => 'AutoMobilku', 'no_telp' => '08123456789', 'alamat' => 'Jl. Soekarno-Hatta No. 123, Malang'],
        ]);
    }
}
