<?php

use Illuminate\Database\Seeder;

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
            'name' => 'Messive Lab',
            'email' => 'mohaiminul.sust@gmail.com',
            'password' => bcrypt('secretadmin'),
        ]);
    }
}
