<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level')->insert([
            ['id_level' => 1, 'kode_level' => 'ADM', 'nama_level' => 'Administrator'],
            ['id_level' => 2, 'kode_level' => 'STF', 'nama_level' => 'Staff'],
        ]);
    }
}
