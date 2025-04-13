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
            ['nama' => 'Defta MNG', 'role' => 'admin', 'username' => 'admin-1', 'password' => Hash::make('admin')],
            ['nama' => 'Ahmad STF', 'role' => 'staff', 'username' => 'staff-1', 'password' => Hash::make('staff')],
        ]);
    }
}
