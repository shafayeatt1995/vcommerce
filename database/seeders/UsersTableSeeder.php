<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Shafayet Al-Anik',
            'role_id' => '1',
            'email' => 'shafayetalanik@gmail.com',
            'password' => bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. Admin',
            'role_id' => '1',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. Seller',
            'role_id' => '2',
            'email' => 'seller@gmail.com',
            'password' => bcrypt('123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. User',
            'role_id' => '3',
            'email' => 'user@gmail.com',
            'password' => bcrypt('123'),
        ]);
    }
}
