<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([[
            'name' => 'Rivaldo Admin',
            'email' => 'adminoi@gmail.com',
            'password' => Hash::make('admingacor'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ],[
            'name' => 'Rivaldo Kasir',
            'email' => 'Kasiroi@gmail.com',
            'password' => Hash::make('Kasirgacor'),
            'role' => 'kasir',
            'created_at' => now(),
            'updated_at' => now()
        ]
        ]
    );
    }
}
