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
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'jay',
                'email' => 'jay@gmail.com',
                'password' => Hash::make('jay12345')
            ],
            [
                'name' => 'tiara',
                'email' => 'tiara@gmail.com',
                'password' => Hash::make('tiara123')
            ],
            [
                'name' => 'sabrina',
                'email' => 'sabrina@gmail.com',
                'password' => Hash::make('sabrina123')
            ]
        ];

        DB::table('users')->insert($data);
    }
}
