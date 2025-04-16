<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            LevelSeeder::class,
            UsersSeeder::class,
            SupplierSeeder::class,
            BarangSeeder::class,
            SupplySeeder::class,
            TokoSeeder::class,
            AksesSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
