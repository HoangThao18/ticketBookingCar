<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => "user" . $i,
                'email' => "user" . $i . '@gmail.com',
                'password' => Hash::make('thao12345'),
                'phone_number' => Faker::create()->phoneNumber,
                'address' => Faker::create()->address,
                'role' => Faker::create()->randomElement(['admin', 'driver', 'user']),
            ]);
        }
    }
}
