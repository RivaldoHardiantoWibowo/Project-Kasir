<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction')->insert([
            'member_id' => 1,
            'total_price' => 40000,
            'user_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('detail_transacts')->insert([
            'transaction_id' => 1,
            'product_id' => 1,
            'qty'=> 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
