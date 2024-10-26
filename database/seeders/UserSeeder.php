<?php

namespace Database\Seeders;

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
        DB::table('users')->insert([
            [
                'name' => 'Carlos Daniel Medina Sahagún',
                'email' => 'carlos@netcommerce.mx',
                'password' => Hash::make('12345'),
                'profile_picture' => 'users/images/default_user_picture.png',
                'role_id' => '1'
            ]
        ]);
    }
}
