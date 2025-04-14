<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supply')->insert([
            ['id_barang' => 1, 'id_user' => 1, 'jumlah' => 2, 'harga_beli' => 1800000000],
        ]);
    }
}
