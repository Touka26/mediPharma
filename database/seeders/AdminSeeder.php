<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert(array(
            array(
                'first_name' => 'Touka',
                'last_name' => 'Ramadan ',
                'email' => 'admin1@gmail.com',
                'password' => bcrypt('123456789'),
            ),
            array(
                'first_name' => 'Mina',
                'last_name' => 'Farhat ',
                'email' => 'admin2@gmail.com',
                'password' => bcrypt('1234567890'),
            ),
        ));
    }
}
