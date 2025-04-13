<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supplier')->insert([
            [
                'kode_supplier' => 'SUP-MBZ',
                'nama_supplier' => 'Mercedes-Benz Indonesia',
                'alamat' => 'Jl. TB Simatupang No.10, Jakarta Selatan',
            ],
            [
                'kode_supplier' => 'SUP-BMW',
                'nama_supplier' => 'BMW Indonesia',
                'alamat' => 'Jl. Gaya Motor Selatan No.1, Jakarta Utara',
            ],
            [
                'kode_supplier' => 'SUP-PRS',
                'nama_supplier' => 'Porsche Indonesia',
                'alamat' => 'Jl. Sudirman No.89, Jakarta Pusat',
            ],
            [
                'kode_supplier' => 'SUP-LMB',
                'nama_supplier' => 'Lamborghini Jakarta',
                'alamat' => 'Jl. S. Parman No.21, Jakarta Barat',
            ],
            [
                'kode_supplier' => 'SUP-JEP',
                'nama_supplier' => 'Jeep Indonesia',
                'alamat' => 'Jl. Daan Mogot No.99, Jakarta Barat',
            ],
            [
                'kode_supplier' => 'SUP-TYT',
                'nama_supplier' => 'Toyota Astra Motor',
                'alamat' => 'Jl. Yos Sudarso No.11, Jakarta Utara',
            ],
        ]);
    }
}
