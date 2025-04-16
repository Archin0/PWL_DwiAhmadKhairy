<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id_level' => 1, 'nama' => 'Defta MNG', 'username' => 'admin-1', 'password' => Hash::make('admin')],
            ['id_level' => 2, 'nama' => 'Ahmad STF', 'username' => 'staff-1', 'password' => Hash::make('staff')],
        ]);
    }
}
