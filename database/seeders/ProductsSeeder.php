<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'Baygon khusus jangkrik',
            'price' => 20000,
            'image' => 'baygon.png',
            'stock' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
